<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\SparepartPart;
use App\Models\Stok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    /**
     * Display a listing of user orders/requests.
     */
    public function index()
    {
        $userId = auth()->id();
        $sessionId = session()->getId();

        $dbOrders = Order::with('items')
            ->where(function ($q) use ($userId, $sessionId) {
                if ($userId) {
                    $q->where('user_id', $userId);
                } else {
                    $q->where('session_id', $sessionId);
                }
            })
            ->oldest()
            ->get();

        $orders = $dbOrders->map(function ($order) {
            return [
                'id' => $order->id,
                'order_code' => $order->order_code,
                'created_at' => $order->created_at ? $order->created_at->toDateTimeString() : '',
                'created_at_formatted' => $order->created_at ? $order->created_at->translatedFormat('d F Y, H:i').' WIB' : '',
                'status' => $order->status,
                'total_types' => $order->total_types,
                'total_items' => $order->total_items,
                'total_price' => (float) $order->total_price,
                'total_price_formatted' => 'Rp '.number_format($order->total_price, 0, ',', '.'),
                'items' => $order->items->map(function ($item) {
                    return [
                        'sparepart_id' => $item->sparepart_id,
                        'sparepart_nama' => $item->sparepart_nama,
                        'vehicle_id' => $item->vehicle_id,
                        'ref' => $item->ref,
                        'kode_part' => $item->kode_part,
                        'nama_part' => $item->nama_part,
                        'jumlah' => (int) $item->jumlah,
                        'harga_satuan' => (float) $item->harga_satuan,
                        'subtotal' => (float) $item->subtotal,
                    ];
                })->toArray(),
            ];
        })->toArray();

        return view('user.orders.index', compact('orders'));
    }

    /**
     * Store a new order from cart items (Checkout).
     */
    public function store(Request $request)
    {
        $userId = auth()->id();
        $sessionId = session()->getId();

        $order = DB::transaction(function () use ($userId, $sessionId) {
            $cartItems = Cart::where(function ($q) use ($userId, $sessionId) {
                if ($userId) {
                    $q->where('user_id', $userId);
                } else {
                    $q->where('session_id', $sessionId);
                }
            })->lockForUpdate()->get();

            if ($cartItems->isEmpty()) {
                return null;
            }

            $requiredStock = [];
            $parts = [];
            $orderItems = [];
            foreach ($cartItems as $item) {
                $mappings = SparepartPart::with('part')
                    ->where('sparepart_id', $item->sparepart_id)
                    ->where('nomor_referensi', trim((string) $item->ref))
                    ->get();

                $mapping = $mappings->first(function ($mapping) use ($item) {
                    $partCode = $mapping->part->kode_parts ?? $mapping->part->kode_part ?? '';

                    return strcasecmp(trim((string) $partCode), trim((string) $item->kode_part)) === 0;
                });

                if (! $mapping || ! $mapping->part) {
                    throw ValidationException::withMessages([
                        'cart' => "Part {$item->nama_part} tidak lagi tersedia pada sparepart yang dipilih.",
                    ]);
                }

                $partId = $mapping->part_id;
                $parts[$partId] = $mapping->part;
                $requiredStock[$partId] = ($requiredStock[$partId] ?? 0) + (int) $item->jumlah;
                $orderItems[] = [
                    'cart_item' => $item,
                    'part' => $mapping->part,
                ];
            }

            $stocks = Stok::whereIn('part_id', array_keys($requiredStock))
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->groupBy('part_id');

            $unitPrices = [];
            foreach ($requiredStock as $partId => $quantity) {
                $partStocks = $stocks->get($partId, collect());
                $available = $partStocks->sum(fn ($stock) => max(0, (int) $stock->jumlah));

                if ($available < $quantity) {
                    $partName = $parts[$partId]->nama_parts ?? $parts[$partId]->nama_part;

                    throw ValidationException::withMessages([
                        'stok' => "Stok {$partName} tidak mencukupi. Tersedia {$available}, dibutuhkan {$quantity}.",
                    ]);
                }

                $unitPrices[$partId] = (float) ($partStocks->first()->harga_satuan ?? 0);
            }

            $totalItems = $cartItems->sum('jumlah');
            $totalPrice = array_reduce($orderItems, function ($total, $orderItem) use ($unitPrices) {
                $partId = $orderItem['part']->id;

                return $total + ($orderItem['cart_item']->jumlah * $unitPrices[$partId]);
            }, 0);
            $order = Order::create([
                'order_code' => 'ORD-'.date('Ymd').'-'.strtoupper(Str::random(4)),
                'user_id' => $userId,
                'session_id' => $sessionId,
                'status' => 'Menunggu Konfirmasi',
                'total_types' => $cartItems->count(),
                'total_items' => $totalItems,
                'total_price' => $totalPrice,
            ]);

            foreach ($orderItems as $orderItem) {
                $item = $orderItem['cart_item'];
                $part = $orderItem['part'];
                $unitPrice = $unitPrices[$part->id];
                $subtotal = $item->jumlah * $unitPrice;

                OrderItem::create([
                    'order_id' => $order->id,
                    'sparepart_id' => $item->sparepart_id,
                    'sparepart_nama' => $item->sparepart_nama,
                    'vehicle_id' => $item->vehicle_id,
                    'ref' => $item->ref,
                    'kode_part' => $part->kode_parts ?? $part->kode_part,
                    'nama_part' => $part->nama_parts ?? $part->nama_part,
                    'jumlah' => $item->jumlah,
                    'harga_satuan' => $unitPrice,
                    'subtotal' => $subtotal,
                ]);
            }

            foreach ($requiredStock as $partId => $quantity) {
                foreach ($stocks->get($partId) as $stock) {
                    $deduct = min(max(0, (int) $stock->jumlah), $quantity);
                    if ($deduct > 0) {
                        $stock->decrement('jumlah', $deduct);
                        $quantity -= $deduct;
                    }
                    if ($quantity === 0) {
                        break;
                    }
                }
            }

            Cart::where(function ($q) use ($userId, $sessionId) {
                if ($userId) {
                    $q->where('user_id', $userId);
                } else {
                    $q->where('session_id', $sessionId);
                }
            })->delete();

            return $order;
        });

        if (! $order) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Keranjang belanja Anda masih kosong.',
                ], 422);
            }

            return redirect()->route('cart.index')->with('error', 'Keranjang Anda masih kosong.');
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Pengajuan sparepart berhasil dibuat! (Kode: {$order->order_code})",
                'order_code' => $order->order_code,
                'redirect_url' => route('orders.index'),
            ]);
        }

        return redirect()->route('orders.index')->with('success', "Pengajuan sparepart berhasil dibuat dengan Kode Pesanan {$order->order_code}!");
    }

}

<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Sparepart;
use App\Models\SparepartPart;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    /**
     * Helper to retrieve cart array from database.
     */
    private function getCartItems()
    {
        $userId = auth()->id();
        $sessionId = session()->getId();

        $query = Cart::query();
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }

        $dbItems = $query->get();

        $cart = [];
        foreach ($dbItems as $item) {
            $cart[$item->key] = [
                'key' => $item->key,
                'sparepart_id' => $item->sparepart_id,
                'sparepart_nama' => $item->sparepart_nama,
                'vehicle_id' => $item->vehicle_id,
                'ref' => $item->ref,
                'kode_part' => $item->kode_part,
                'nama_part' => $item->nama_part,
                'jumlah' => (int) $item->jumlah,
                'harga_satuan' => (float) $item->harga_satuan,
                'stok_tersedia' => (int) $item->stok_tersedia,
            ];
        }
        return $cart;
    }

    /**
     * Display the cart page.
     */
    public function index()
    {
        $cart = $this->getCartItems();
        return view('user.cart.index', compact('cart'));
    }

    /**
     * Add items to the cart via AJAX or Form POST.
     */
    public function add(Request $request)
    {
        $validated = $request->validate([
            'sparepart_id' => 'required|exists:sparepart,id',
            'vehicle_id' => 'nullable|integer',
            'items' => 'required|array|min:1',
            'items.*.ref' => 'required|string',
            'items.*.kode' => 'required|string',
            'items.*.nama' => 'required|string',
            'items.*.jml' => 'required|integer|min:1',
            'items.*.stok' => 'nullable|integer',
        ]);

        $sparepart = Sparepart::findOrFail($validated['sparepart_id']);
        $sparepartNama = $sparepart->nama_sparepart;
        $vehicleId = $validated['vehicle_id'] ?? null;

        $userId = auth()->id();
        $sessionId = session()->getId();
        $addedCount = 0;

        foreach ($validated['items'] as $item) {
            $key = $validated['sparepart_id'] . '_' . trim($item['ref']) . '_' . trim($item['kode']);
            $mapping = SparepartPart::with(['part.stok'])
                ->where('sparepart_id', $validated['sparepart_id'])
                ->where('nomor_referensi', trim($item['ref']))
                ->get()
                ->first(function ($mapping) use ($item) {
                    $partCode = $mapping->part->kode_parts ?? $mapping->part->kode_part ?? '';

                    return strcasecmp(trim((string) $partCode), trim((string) $item['kode'])) === 0;
                });

            if (! $mapping || ! $mapping->part) {
                throw ValidationException::withMessages([
                    'items' => "Part {$item['nama']} tidak lagi tersedia pada sparepart yang dipilih.",
                ]);
            }

            $cartItem = Cart::where(function ($q) use ($userId, $sessionId) {
                if ($userId) {
                    $q->where('user_id', $userId);
                } else {
                    $q->where('session_id', $sessionId);
                }
            })->where('key', $key)->first();

            $part = $mapping->part;
            $partCode = $part->kode_parts ?? $part->kode_part;
            $partName = $part->nama_parts ?? $part->nama_part;
            $unitPrice = (float) ($part->stok->harga_satuan ?? 0);
            $availableStock = (int) ($part->stok->jumlah ?? 0);

            if ($cartItem) {
                $cartItem->jumlah += (int) $item['jml'];
                $cartItem->harga_satuan = $unitPrice;
                $cartItem->stok_tersedia = $availableStock;
                $cartItem->save();
            } else {
                Cart::create([
                    'user_id' => $userId,
                    'session_id' => $sessionId,
                    'key' => $key,
                    'sparepart_id' => $validated['sparepart_id'],
                    'sparepart_nama' => $sparepartNama,
                    'vehicle_id' => $vehicleId,
                    'ref' => trim($item['ref']),
                    'kode_part' => $partCode,
                    'nama_part' => $partName,
                    'jumlah' => (int) $item['jml'],
                    'harga_satuan' => $unitPrice,
                    'stok_tersedia' => $availableStock,
                ]);
            }
            $addedCount++;
        }

        $cart = $this->getCartItems();
        $totalItemsCount = array_sum(array_column($cart, 'jumlah'));

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "{$addedCount} part berhasil ditambahkan ke keranjang!",
                'cart_count' => count($cart),
                'total_items' => $totalItemsCount,
                'cart_url' => route('cart.index'),
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Part berhasil ditambahkan ke keranjang!');
    }

    /**
     * Update quantity of a specific cart item.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string',
            'jumlah' => 'required|integer|min:1',
        ]);

        $userId = auth()->id();
        $sessionId = session()->getId();

        $cartItem = Cart::where(function ($q) use ($userId, $sessionId) {
            if ($userId) {
                $q->where('user_id', $userId);
            } else {
                $q->where('session_id', $sessionId);
            }
        })->where('key', $validated['key'])->first();

        if ($cartItem) {
            $cartItem->jumlah = (int) $validated['jumlah'];
            $cartItem->save();
        }

        $cart = $this->getCartItems();

        if ($request->wantsJson()) {
            $itemSubtotal = isset($cart[$validated['key']])
                ? $cart[$validated['key']]['jumlah'] * $cart[$validated['key']]['harga_satuan']
                : 0;

            $cartTotal = array_reduce($cart, function ($total, $item) {
                return $total + ($item['jumlah'] * $item['harga_satuan']);
            }, 0);

            return response()->json([
                'success' => true,
                'message' => 'Jumlah part berhasil diperbarui!',
                'item_subtotal' => $itemSubtotal,
                'item_subtotal_formatted' => 'Rp ' . number_format($itemSubtotal, 0, ',', '.'),
                'cart_total' => $cartTotal,
                'cart_total_formatted' => 'Rp ' . number_format($cartTotal, 0, ',', '.'),
                'total_items' => array_sum(array_column($cart, 'jumlah')),
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Jumlah part diperbarui.');
    }

    /**
     * Remove an item from the cart.
     */
    public function remove(Request $request, $key)
    {
        $userId = auth()->id();
        $sessionId = session()->getId();

        Cart::where(function ($q) use ($userId, $sessionId) {
            if ($userId) {
                $q->where('user_id', $userId);
            } else {
                $q->where('session_id', $sessionId);
            }
        })->where('key', $key)->delete();

        $cart = $this->getCartItems();

        if ($request->wantsJson()) {
            $cartTotal = array_reduce($cart, function ($total, $item) {
                return $total + ($item['jumlah'] * $item['harga_satuan']);
            }, 0);

            return response()->json([
                'success' => true,
                'message' => 'Part berhasil dihapus dari keranjang.',
                'cart_count' => count($cart),
                'total_items' => array_sum(array_column($cart, 'jumlah')),
                'cart_total_formatted' => 'Rp ' . number_format($cartTotal, 0, ',', '.'),
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Part dihapus dari keranjang.');
    }

    /**
     * Clear the entire cart.
     */
    public function clear(Request $request)
    {
        $userId = auth()->id();
        $sessionId = session()->getId();

        Cart::where(function ($q) use ($userId, $sessionId) {
            if ($userId) {
                $q->where('user_id', $userId);
            } else {
                $q->where('session_id', $sessionId);
            }
        })->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Keranjang berhasil dikosongkan.',
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Keranjang dikosongkan.');
    }
}

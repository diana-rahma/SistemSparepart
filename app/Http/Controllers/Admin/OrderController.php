<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search', ''));
        $status = trim((string) $request->input('status', ''));

        $orders = Order::with(['user:id,name', 'items'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($orderQuery) use ($search) {
                    $orderQuery->where('order_code', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($status !== '', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->oldest()
            ->paginate(15)
            ->withQueryString();

        $statuses = [
            'Menunggu Konfirmasi',
            'Diproses',
            'Siap Diambil',
            'Selesai',
            'Dibatalkan',
        ];

        return view('admin.orders.index', compact('orders', 'search', 'status', 'statuses'));
    }

    public function show($id)
    {
        return redirect()->route('admin.orders.edit', $id);
    }

    public function edit($id)
    {
        $order = Order::with(['user', 'items'])->findOrFail($id);

        $statuses = [
            'Menunggu Konfirmasi',
            'Diproses',
            'Siap Diambil',
            'Selesai',
            'Dibatalkan',
        ];

        return view('admin.orders.edit', compact('order', 'statuses'));
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|string|in:Menunggu Konfirmasi,Diproses,Siap Diambil,Selesai,Dibatalkan',
        ], [
            'status.required' => 'Status pesanan wajib dipilih.',
            'status.in' => 'Status pesanan yang dipilih tidak valid.',
        ]);

        $oldStatus = $order->status;
        $order->status = $validated['status'];
        $order->save();

        return redirect()->route('admin.orders.index')
            ->with('success', "Status pesanan {$order->order_code} berhasil diperbarui dari '{$oldStatus}' menjadi '{$order->status}'.");
    }
}

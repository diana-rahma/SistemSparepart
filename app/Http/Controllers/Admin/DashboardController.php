<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Parts;
use App\Models\Stok;
use App\Models\User;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $readyParts = Parts::whereHas('stok', function ($query) {
            $query->where('jumlah', '>', 0);
        })->count();

        $todayOrders = Order::whereDate('created_at', today())->count();
        $registeredUsers = User::count();
        $lowStockParts = Stok::where('jumlah', '<=', 5)->count();
        $recentOrders = Order::with('user:id,name')->oldest()->take(5)->get();

        return view('admin.dashboard', compact(
            'readyParts',
            'todayOrders',
            'registeredUsers',
            'lowStockParts',
            'recentOrders'
        ));
    }
}

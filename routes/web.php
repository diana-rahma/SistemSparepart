<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\User\SparepartController as UserSparepartController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ModelController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PartsController;
use App\Http\Controllers\Admin\SparepartController;
use App\Http\Controllers\Admin\StokController;
use App\Http\Controllers\Admin\TahunController;
use App\Http\Controllers\Admin\TipeKendaraanController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\ProfileController;

// Halaman Landing Page
Route::get('/', function () {
    return view('landing_page.index');
})->name('home');

Route::get('/landing', function () {
    return view('landing_page.index');
})->name('landing');

// Rute Admin (Hanya untuk pengguna dengan role admin)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::resource('kategori', KategoriController::class);
    Route::resource('model', ModelController::class);
    Route::resource('stok', StokController::class);
    Route::resource('orders', AdminOrderController::class)->only(['index', 'show', 'edit', 'update']);

    // Tipe Kendaraan / Sarana & Relasi Sparepart
    Route::get('tipe_kendaraan/{id}/spareparts', [TipeKendaraanController::class, 'spareparts'])->name('tipe_kendaraan.spareparts');
    Route::post('tipe_kendaraan/{id}/spareparts', [TipeKendaraanController::class, 'storeSpareparts'])->name('tipe_kendaraan.spareparts.store');
    Route::delete('tipe_kendaraan/{id}/spareparts/{sparepartId}', [TipeKendaraanController::class, 'destroySparepart'])->name('tipe_kendaraan.spareparts.destroy');
    Route::resource('tipe_kendaraan', TipeKendaraanController::class);

    // Parts Master
    Route::resource('parts', PartsController::class);

    // Sparepart Diagram & Mapping
    Route::get('spareparts/{id}/mapping', [SparepartController::class, 'mapping'])->name('spareparts.mapping');
    Route::post('spareparts/{id}/parts', [SparepartController::class, 'storePart'])->name('spareparts.parts.store');
    Route::delete('spareparts/{id}/parts/{pivotId}', [SparepartController::class, 'destroyPart'])->name('spareparts.parts.destroy');
    Route::delete('spareparts/{id}/penanda/{penandaId}', [SparepartController::class, 'destroyPenanda'])->name('spareparts.penanda.destroy');
    Route::resource('spareparts', SparepartController::class);

    // Data pengguna
    Route::resource('user', \App\Http\Controllers\Admin\UserController::class)
        ->names('pengguna')
        ->parameters(['user' => 'users']);
});

// Rute Pembeli / Pengguna (Hanya bisa diakses setelah login: Katalog, Keranjang, Pesanan, Profil)
Route::middleware('auth')->group(function () {
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/', function () {
            return redirect()->route('spareparts.index');
        })->name('index');
    });

    // Fitur Katalog Sparepart
    Route::get('/spareparts', [UserSparepartController::class, 'index'])->name('spareparts.index');
    Route::get('/spareparts/{id}', [UserSparepartController::class, 'show'])->name('spareparts.show');
    Route::get('/spareparts/{vehicleId}/sparepart/{sparepartId}', [UserSparepartController::class, 'detail'])->name('spareparts.detail');
    Route::get('/spareparts/{vehicleId}/part/{partId}', [UserSparepartController::class, 'detail']);

    // Fitur Keranjang
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{key}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

    // Fitur Pesanan
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::patch('/orders/{orderId}/status', [AdminOrderController::class, 'update'])->middleware('admin')->name('orders.update_status');

    // Fitur Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


});

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'index'])
//         ->name('profile.index');

//     Route::put('/profile', [ProfileController::class, 'update'])
//         ->name('profile.update');

//     Route::put('/profile/password', [ProfileController::class, 'updatePassword'])
//         ->name('profile.password.update');
// });

// Dashboard Redirect (Breeze Auth)
Route::get('/dashboard', function () {
    if (auth()->check() && auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('spareparts.index');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

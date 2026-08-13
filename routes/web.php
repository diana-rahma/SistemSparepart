<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\User\SparepartController as UserSparepartController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\ModelController;
use App\Http\Controllers\Admin\TahunController;
use App\Http\Controllers\Admin\VolumeMesinController;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('kategori', KategoriController::class);
    Route::resource('model', ModelController::class);
    Route::resource('tahun', TahunController::class);
    Route::resource('volume_mesin', VolumeMesinController::class);
});

Route::get('/spareparts', [UserSparepartController::class, 'index'])->name('spareparts.index');
Route::get('/spareparts/{id}', [UserSparepartController::class, 'show'])->name('spareparts.show');
Route::get('/spareparts/{vehicleId}/part/{partId}', [UserSparepartController::class, 'detail'])->name('spareparts.detail');


Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';


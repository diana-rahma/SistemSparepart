<?php

namespace App\Providers;

use App\Models\Cart;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.header_user', function ($view) {
            $userId = auth()->id();
            $sessionId = session()->getId();

            $cartItemCount = Cart::query()
                ->when($userId, function ($query, $userId) {
                    $query->where('user_id', $userId);
                }, function ($query) use ($sessionId) {
                    $query->where('session_id', $sessionId);
                })
                ->count();

            $view->with('cartItemCount', $cartItemCount);
        });
    }
}

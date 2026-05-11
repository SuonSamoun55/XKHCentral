<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\POSModel\Cart;
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
    // Keep existing pagination config
    Paginator::useBootstrapFive();

    // ✅ Share cart count with all views (header, pages, etc.)
    View::composer('*', function ($view) {

        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())
                ->where('status', 'active')
                ->with('items')
                ->first();

            $cartCount = $cart ? $cart->items->sum('qty') : 0;
        } else {
            $cartCount = 0;
        }

        $view->with('cartCount', $cartCount);
    });
}
}

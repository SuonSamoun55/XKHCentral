<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;
use App\Models\POS\Cart;
use App\Models\ManagementSystem\Company;

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
        // Force HTTPS on Railway production
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        // Keep existing pagination config
        Paginator::useBootstrapFive();

        // Share cart count with all views
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
            $view->with('activeFaviconUrl', $this->resolveActiveFaviconUrl());
        });
    }

    /**
     * The favicon of the currently selected company, falling back to the
     * default app icon when none is set. Memoized per-request since this
     * composer runs once for every view/partial rendered on the page.
     */
    private ?string $resolvedFaviconUrl = null;
    private bool $faviconResolved = false;

    private function resolveActiveFaviconUrl(): string
    {
        if ($this->faviconResolved) {
            return $this->resolvedFaviconUrl;
        }

        $this->faviconResolved = true;
        $this->resolvedFaviconUrl = asset('images/pos/xtricate.png');

        $companyId = session('selected_company_id');

        if ($companyId) {
            $favicon = Company::whereKey($companyId)->value('favicon');

            if (!empty($favicon) && Storage::disk('public')->exists($favicon)) {
                $this->resolvedFaviconUrl = asset('storage/' . $favicon);
            }
        }

        return $this->resolvedFaviconUrl;
    }
}

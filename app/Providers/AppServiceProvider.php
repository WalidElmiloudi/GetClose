<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Client\CartController;

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
        // Share cart count with all views
        View::composer('*', function ($view) {
            if (auth()->check() && auth()->user()->role === 'client') {
                $cartController = new CartController();
                $view->with('cartCount', $cartController->getCount());
            } else {
                $view->with('cartCount', 0);
            }
        });
    }
}

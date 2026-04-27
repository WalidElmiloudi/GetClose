<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Client\CartController;
use App\Services\NotificationService;

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
        // Share cart count and notification count with all views
        View::composer('*', function ($view) {
            if (auth()->check() && auth()->user()->role === 'client') {
                $cartController = new CartController();
                $view->with('cartCount', $cartController->getCount());
                $view->with('cartHelper', $cartController);
            } else {
                $view->with('cartCount', 0);
                $view->with('cartHelper', null);
            }
            
            // Share notification count for authenticated users
            if (auth()->check()) {
                $notificationService = new NotificationService();
                $view->with('notificationCount', $notificationService->getUnreadCount(auth()->id()));
            } else {
                $view->with('notificationCount', 0);
            }
        });
    }
}

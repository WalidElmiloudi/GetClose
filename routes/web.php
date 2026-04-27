<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Client\ProductController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\OrderController;
use App\Http\Controllers\Client\CheckoutController;
use App\Http\Controllers\Client\ReviewController;
use App\Http\Controllers\Client\SearchController;
use App\Http\Controllers\Vendor\ShopController;
use App\Http\Controllers\Vendor\ProductController as VendorProductController;
use App\Http\Controllers\Vendor\CategoryController;
use App\Http\Controllers\Vendor\DashboardController as VendorDashboardController;
use App\Http\Controllers\Vendor\OrderController as VendorOrderController;
use App\Http\Controllers\Vendor\PayoutController as VendorPayoutController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ShopController as AdminShopController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\DisputeController as AdminDisputeController;
use App\Http\Controllers\Admin\ShippingMethodController as AdminShippingController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Public routes
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/products', [ProductController::class, 'index'])->name('products');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/shops', [ShopController::class, 'index'])->name('shops');
Route::get('/shops/{shop}', [ShopController::class, 'show'])->name('shops.show');
Route::get('/shops/{shop}/category/{category}', [ShopController::class, 'showByCategory'])->name('shops.category');
Route::get('/shops/{shop}/search', [ShopController::class, 'search'])->name('shops.search');
Route::get('/categories/{shop}', [CategoryController::class, 'show'])->name('categories.show');

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.view');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Client routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::put('/cart/{item}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{item}', [CartController::class, 'destroy'])->name('cart.destroy');
    
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::delete('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{order}/dispute', [OrderController::class, 'createDispute'])->name('orders.dispute');
    
    // Checkout routes
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::post('/checkout/create-payment-intent', [CheckoutController::class, 'createPaymentIntent'])->name('checkout.create-payment-intent');
    Route::get('/checkout/confirmation/{order}', [CheckoutController::class, 'confirmation'])->name('orders.confirmation');
    
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    
    // Notification routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
    
    // Product reviews
    Route::post('/products/{product}/review', [ProductController::class, 'storeReview'])->name('products.review.store');
    
    // Vendor routes
    Route::get('/vendor/dashboard', [VendorDashboardController::class, 'index'])->name('vendor.dashboard');
    Route::post('/shop', [ShopController::class, 'store'])->name('vendor.shop.store');
    Route::delete('/shop/{shop}', [ShopController::class, 'destroy'])->name('vendor.shop.destroy');
    
    Route::get('/vendor/products', [VendorProductController::class, 'index'])->name('vendor.products');
    Route::get('/vendor/products/create', [VendorProductController::class, 'create'])->name('vendor.products.create');
    Route::post('/vendor/products', [VendorProductController::class, 'store'])->name('vendor.products.store');
    Route::get('/vendor/products/{product}/edit', [VendorProductController::class, 'edit'])->name('vendor.products.edit');
    Route::put('/vendor/products/{product}', [VendorProductController::class, 'update'])->name('vendor.products.update');
    Route::delete('/vendor/products/{product}', [VendorProductController::class, 'destroy'])->name('vendor.products.destroy');
    
    Route::get('/vendor/orders', [VendorOrderController::class, 'index'])->name('vendor.orders');
    Route::get('/vendor/orders/{order}', [VendorOrderController::class, 'show'])->name('vendor.orders.show');
    
    // Vendor financial routes
    Route::get('/vendor/financials', [VendorPayoutController::class, 'dashboard'])->name('vendor.financials');
    Route::get('/vendor/financials/ledger', [VendorPayoutController::class, 'ledger'])->name('vendor.financials.ledger');
    Route::get('/vendor/financials/eligibility', [VendorPayoutController::class, 'eligibility'])->name('vendor.financials.eligibility');
    Route::get('/vendor/payouts', [VendorPayoutController::class, 'index'])->name('vendor.payouts');
    Route::post('/vendor/payouts/request', [VendorPayoutController::class, 'request'])->name('vendor.payouts.request');
    
    Route::post('/vendor/categories', [CategoryController::class, 'store'])->name('vendor.categories.store');
    Route::delete('/vendor/categories/{category}', [CategoryController::class, 'destroy'])->name('vendor.categories.destroy');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Shops
    Route::get('/shops', [AdminShopController::class, 'index'])->name('shops');
    Route::get('/shops/pending', [AdminShopController::class, 'pending'])->name('shops.pending');
    Route::post('/shops/{shop}/approve', [AdminShopController::class, 'approve'])->name('shops.approve');
    Route::delete('/shops/{shop}/reject', [AdminShopController::class, 'reject'])->name('shops.reject');
    Route::delete('/shops/{shop}', [AdminShopController::class, 'destroy'])->name('shops.destroy');
    
    // Users
    Route::get('/users', [AdminUserController::class, 'index'])->name('users');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    
    // Products
    Route::get('/products', [AdminProductController::class, 'index'])->name('products');
    Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');
    
    // Orders
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders');
    Route::put('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    
    // Disputes
    Route::get('/disputes', [AdminDisputeController::class, 'index'])->name('disputes');
    Route::post('/disputes/{dispute}/resolve', [AdminDisputeController::class, 'resolve'])->name('disputes.resolve');
    
    // Shipping Methods
    Route::get('/shipping', [AdminShippingController::class, 'index'])->name('shipping');
    Route::post('/shipping', [AdminShippingController::class, 'store'])->name('shipping.store');
    Route::put('/shipping/{shippingMethod}', [AdminShippingController::class, 'update'])->name('shipping.update');
    Route::delete('/shipping/{shippingMethod}', [AdminShippingController::class, 'destroy'])->name('shipping.destroy');
});

require __DIR__.'/auth.php';

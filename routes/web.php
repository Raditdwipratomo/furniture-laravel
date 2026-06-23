<?php

use Illuminate\Support\Facades\Route;

// Auth Controllers
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;

// Public Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SearchController;

// Cart & Checkout Controllers
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;

// API Controller
use App\Http\Controllers\ApiController;

// Customer Controllers
use App\Http\Controllers\Customer\AccountController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Customer\AddressController;
use App\Http\Controllers\Customer\CustomerOrderController;
use App\Http\Controllers\Customer\WishlistController;
use App\Http\Controllers\Customer\CustomerReviewController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\BannerController as AdminBannerController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::get('/search', [SearchController::class, 'index'])->name('search');

/*
|--------------------------------------------------------------------------
| Authentication Routes (Guest Only)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| Logout Route (Authenticated)
|--------------------------------------------------------------------------
*/
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Cart Routes
|--------------------------------------------------------------------------
*/
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('/add', [CartController::class, 'add'])->name('cart.add');
    Route::put('/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/{id}', [CartController::class, 'remove'])->name('cart.remove');
});

/*
|--------------------------------------------------------------------------
| API / AJAX Routes
|--------------------------------------------------------------------------
*/
Route::prefix('api')->group(function () {
    Route::get('/provinces', [ApiController::class, 'getProvinces'])->name('api.provinces');
    Route::get('/cities/{provinceId}', [ApiController::class, 'getCities'])->name('api.cities');
    Route::post('/shipping-cost', [ApiController::class, 'shippingCost'])->name('api.shipping-cost');
    Route::post('/coupon/validate', [ApiController::class, 'validateCoupon'])->name('api.validate-coupon');
});

/*
|--------------------------------------------------------------------------
| Authenticated Customer Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // Checkout
    Route::prefix('checkout')->group(function () {
        Route::get('/', [CheckoutController::class, 'index'])->name('checkout.index');
        Route::post('/', [CheckoutController::class, 'store'])->name('checkout.store');
        Route::get('/success/{no_pesanan}', [CheckoutController::class, 'success'])->name('checkout.success');
    });

    // Payment
    Route::post('/payment/snap-token', [PaymentController::class, 'createSnapToken'])->name('payment.snap-token');
    Route::post('/payment/handle', [PaymentController::class, 'handle'])->name('payment.handle');

    // Wishlist toggle (AJAX)
    Route::post('/api/wishlist/toggle', [WishlistController::class, 'toggle'])->name('customer.wishlist.toggle');

    // Customer Account
    Route::prefix('customer')->name('customer.')->group(function () {
        // Dashboard
        Route::get('/account', [AccountController::class, 'index'])->name('account.index');

        // Profile
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');

        // Addresses
        Route::resource('addresses', AddressController::class)->names('addresses');

        // Orders
        Route::get('/orders', [CustomerOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{no_pesanan}', [CustomerOrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{no_pesanan}/cancel', [CustomerOrderController::class, 'cancel'])->name('orders.cancel');

        // Wishlist
        Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
        Route::delete('/wishlist/{id}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');

        // Reviews
        Route::get('/reviews', [CustomerReviewController::class, 'index'])->name('reviews.index');
        Route::post('/reviews', [CustomerReviewController::class, 'store'])->name('reviews.store');
    });
});

/*
|--------------------------------------------------------------------------
| Payment Webhook (No CSRF)
|--------------------------------------------------------------------------
*/
Route::post('/payment/notification', [PaymentController::class, 'handleNotification'])->name('payment.notification');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Categories
    Route::resource('categories', AdminCategoryController::class);

    // Products
    Route::resource('products', AdminProductController::class);

    // Orders
    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::put('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::delete('orders/{order}', [AdminOrderController::class, 'destroy'])->name('orders.destroy');

    // Customers
    Route::get('customers', [AdminCustomerController::class, 'index'])->name('customers.index');
    Route::get('customers/{customer}', [AdminCustomerController::class, 'show'])->name('customers.show');
    Route::post('customers/{customer}/toggle', [AdminCustomerController::class, 'toggle'])->name('customers.toggle');

    // Payments
    Route::get('payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::get('payments/{payment}', [AdminPaymentController::class, 'show'])->name('payments.show');

    // Coupons
    Route::resource('coupons', AdminCouponController::class);

    // Banners
    Route::resource('banners', AdminBannerController::class);

    // Reviews
    Route::get('reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::post('reviews/{review}/approve', [AdminReviewController::class, 'approve'])->name('reviews.approve');
    Route::delete('reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');

    // Settings
    Route::get('settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [AdminSettingController::class, 'update'])->name('settings.update');
});

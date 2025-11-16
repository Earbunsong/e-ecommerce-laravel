<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KhqrController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//include 'admin/khqr.php';

//Route::post('/generate-qrcode', [KhqrController::class, 'generateQRCode']);



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::get('/search', [ProductController::class, 'search'])->name('products.search');

// Product Search API (for AJAX live search)
Route::get('/api/products/search', [ProductController::class, 'searchApi'])->name('api.products.search');
Route::get('/api/products', [ProductController::class, 'searchApi'])->name('api.products.index');

// Debug route for testing session/CSRF (remove in production after testing)
Route::get('/debug/session', function () {
    return response()->json([
        'session_id' => session()->getId(),
        'csrf_token' => csrf_token(),
        'session_driver' => config('session.driver'),
        'session_secure' => config('session.secure'),
        'session_domain' => config('session.domain'),
        'session_same_site' => config('session.same_site'),
        'app_env' => config('app.env'),
        'app_url' => config('app.url'),
        'session_works' => session()->has('_token'),
        'cookies' => request()->cookies->all(),
    ]);
})->name('debug.session');

// Cart Routes - FIXED
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Cart API Routes (for AJAX calls with CSRF protection)
Route::prefix('api/cart')->name('api.cart.')->group(function () {
    Route::post('/add/{id}', [CartController::class, 'addApi'])->name('add');
    Route::post('/update/{id}', [CartController::class, 'updateApi'])->name('update');
    Route::post('/remove/{id}', [CartController::class, 'removeApi'])->name('remove');
    Route::get('/count', [CartController::class, 'count'])->name('count');
});

// Wishlist Routes
Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
Route::post('/wishlist/toggle/{id}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
Route::post('/wishlist/remove/{id}', [WishlistController::class, 'remove'])->name('wishlist.remove');

// Checkout
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');

// KHQR Payment Routes
Route::get('/payment/khqr', [KhqrController::class, 'show'])->name('payment.khqr.show');
Route::post('/payment/khqr/check', [KhqrController::class, 'checkTransaction'])->name('payment.khqr.check');
Route::post('/payment/khqr/verify', [KhqrController::class, 'verifyPayment'])->name('payment.khqr.verify');

// Admin Dashboard
Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

// Admin Categories
Route::prefix('admin/categories')->name('admin.categories.')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('index');
    Route::get('/create', [CategoryController::class, 'create'])->name('create');
    Route::post('/', [CategoryController::class, 'store'])->name('store');
    Route::get('/{category}', [CategoryController::class, 'show'])->name('show');
    Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
    Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
    Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
    Route::patch('/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('toggle-status');
});

// Admin Products
Route::prefix('admin/products')->name('admin.products.')->group(function () {
    Route::get('/', [AdminProductController::class, 'index'])->name('index');
    Route::get('/create', [AdminProductController::class, 'create'])->name('create');
    Route::post('/', [AdminProductController::class, 'store'])->name('store');
    Route::get('/{product}', [AdminProductController::class, 'show'])->name('show');
    Route::get('/{product}/edit', [AdminProductController::class, 'edit'])->name('edit');
    Route::put('/{product}', [AdminProductController::class, 'update'])->name('update');
    Route::delete('/{product}', [AdminProductController::class, 'destroy'])->name('destroy');
    Route::patch('/{product}/toggle-status', [AdminProductController::class, 'toggleStatus'])->name('toggle-status');
    Route::patch('/{product}/toggle-featured', [AdminProductController::class, 'toggleFeatured'])->name('toggle-featured');
});

require __DIR__.'/auth.php';

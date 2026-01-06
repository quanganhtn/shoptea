<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MyOrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Trang chủ & sản phẩm
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/products/{id}', [HomeController::class, 'show'])
    ->name('products.show');
Route::get('/search-suggest', [HomeController::class, 'searchSuggest'])
    ->name('search.suggest');
/*
|--------------------------------------------------------------------------
| Guest – Chưa đăng nhập
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {

    // Register
    Route::get('/register', [AuthController::class, 'showRegisterForm'])
        ->name('register');
    Route::post('/register', [AuthController::class, 'register'])
        ->name('register.post');

    // Login
    Route::get('/login', [AuthController::class, 'showLoginForm'])
        ->name('login');
    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.post');

    //google
    Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])
        ->name('auth.google.redirect');
    Route::get('/auth/google/callback', [GoogleController::class, 'callback'])
        ->name('auth.google.callback');
});

/*
|--------------------------------------------------------------------------
| Auth – Đã đăng nhập
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::get('/checkout', [CheckoutController::class, 'index'])
        ->name('checkout');
    Route::post('/checkout/placeOrder', [CheckoutController::class, 'placeOrder'])
        ->name('checkout.placeOrder');

    Route::get('/my-orders', [MyOrderController::class, 'index'])->name('orders.my');
    Route::get('/my-orders/{id}', [MyOrderController::class, 'show'])->name('orders.show');
    
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
});

/*
|--------------------------------------------------------------------------
| Giỏ hàng
|--------------------------------------------------------------------------
*/
Route::get('/cart', [CartController::class, 'index'])
    ->name('cart.index');

Route::post('/cart/add', [CartController::class, 'add'])
    ->name('cart.add');

Route::post('/cart/update', [CartController::class, 'update'])
    ->name('cart.update');

Route::post('/cart/delete-selected', [CartController::class, 'deleteSelected'])
    ->name('cart.deleteSelected');


Route::middleware('auth')->group(function () {
    Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])
        ->name('review.store');
});

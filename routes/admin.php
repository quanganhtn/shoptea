<?php

use App\Http\Controllers\Admin\AdminInboxController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HomepageController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'isAdmin'])
    ->group(function () {

        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Homepage settings
        Route::prefix('homepage')
            ->name('homepage.')
            ->group(function () {
                Route::get('/hero', [HomepageController::class, 'hero'])->name('hero');
                Route::post('/hero', [HomepageController::class, 'updateHero'])->name('hero.update');

                Route::get('/about', [HomepageController::class, 'about'])->name('about');
                Route::post('/about', [HomepageController::class, 'updateAbout'])->name('about.update');

                Route::get('/news', [HomepageController::class, 'news'])->name('news');
                Route::post('/news', [HomepageController::class, 'updateNews'])->name('news.update');

                Route::get('/contact', [HomepageController::class, 'contact'])->name('contact');
                Route::post('/contact', [HomepageController::class, 'updateContact'])->name('contact.update');
            });

        /* ================= PRODUCTS ================= */
        Route::prefix('products')->name('products.')->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('index');
            Route::get('create', [ProductController::class, 'create'])->name('create');
            Route::post('/', [ProductController::class, 'store'])->name('store');
            Route::get('{id}/edit', [ProductController::class, 'edit'])->name('edit');
            Route::put('{id}', [ProductController::class, 'update'])->name('update');
            Route::delete('{id}', [ProductController::class, 'destroy'])->name('destroy');
        });


        /* ================= CATEGORIES ================= */
        Route::prefix('categories')->name('categories.')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->name('index');
            Route::post('/', [CategoryController::class, 'store'])->name('store');
            Route::put('{id}', [CategoryController::class, 'update'])->name('update');
            Route::delete('{id}', [CategoryController::class, 'destroy'])->name('destroy');
        });

        /* ================= ORDERS ================= */
        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', [OrderController::class, 'index'])->name('index');
            // trang edit (form)
            Route::get('{id}/edit', [OrderController::class, 'edit'])->name('edit');
            // update thông tin đơn
            Route::put('{id}', [OrderController::class, 'update'])->name('update');
            // đổi trạng thái (Xác nhận / Huỷ / Giao / Hoàn thành)
            Route::patch('{id}/status', [OrderController::class, 'updateStatus'])->name('status');
        });

        /* ================= USERS ================= */
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('{id}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('{id}', [UserController::class, 'update'])->name('update');
            Route::delete('{id}', [UserController::class, 'destroy'])->name('destroy');
        });
        /* ================= INBOX ================= */
        Route::prefix('inbox')->name('inbox.')->group(function () {

            
            Route::get('/unread-count', [AdminInboxController::class, 'unreadCount'])
                ->name('unreadCount');

            Route::get('/', [AdminInboxController::class, 'index'])->name('index');

            Route::get('/{conversation}/messages', [AdminInboxController::class, 'messages'])->name('messages');
            Route::post('/{conversation}/send', [AdminInboxController::class, 'send'])->name('send');


            Route::get('/{conversation}', [AdminInboxController::class, 'show'])->name('show');
        });

    });


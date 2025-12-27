<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HomepageController;
use App\Http\Controllers\Admin\ProductController;
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

        // ✅ Products CRUD (đúng name admin.products.*)
        Route::resource('products', ProductController::class)->except(['show']);
        // => admin.products.index, create, store, edit, update, destroy
    });

<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\UserController as AdminUserController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // All routes for /admin/product
    Route::group([
        'prefix' => 'admin',
        'as' => 'admin.'
    ], function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::group([
            'prefix' => 'user',
            'as' => 'user.'
        ], function () {
            Route::get('/', [AdminUserController::class, 'index'])->name('index');
            Route::get('show/{id}', [AdminUserController::class, 'show'])->name('show');
        });

        Route::group([
            'prefix' => 'product',
            'as' => 'product.'
        ], function () {
            Route::get('/', [AdminProductController::class, 'index'])->name('index');
            Route::get('show/{id}', [AdminProductController::class, 'show'])->name('show');
        });
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController as TestProductController;
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

    // Product routes
    Route::prefix('product')->name('product.')->group(function () {
        Route::get('/', [TestProductController::class, 'index'])->name('index');
        Route::get('/create', [TestProductController::class, 'create'])->name('create');
        Route::post('/', [TestProductController::class, 'store'])->name('store');
        Route::get('/{id}', [TestProductController::class, 'show'])->name('show');
        Route::put('/{id}', [TestProductController::class, 'update'])->name('update');
        Route::delete('/{id}', [TestProductController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/edit', [TestProductController::class, 'edit'])->name('edit');
    });

    // Admin routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Admin User routes
        Route::prefix('user')->name('user.')->group(function () {
            Route::get('/', [AdminUserController::class, 'index'])->name('index');
            Route::get('show/{id}', [AdminUserController::class, 'show'])->name('show');
        });

        // Admin Product routes
        Route::prefix('product')->name('product.')->group(function () {
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

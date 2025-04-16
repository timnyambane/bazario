<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Product\ProductController;
use Illuminate\Http\Request;

Route::middleware('auth:sanctum')->get('/user', fn(Request $request) => $request->user());

Route::prefix('admin')->name('admin.')->group(function () {
    Route::post('login', [AdminController::class, 'login'])->name('login');

    Route::middleware(['auth:sanctum', 'admin'])->group(function () {
        Route::post('logout', [AdminController::class, 'logout'])->name('logout');
        Route::get('profile', [AdminController::class, 'profile'])->name('profile');
    });
});

Route::prefix('customer')->name('customer.')->group(function () {
    Route::post('register', [CustomerController::class, 'register'])->name('register');
    Route::post('login', [CustomerController::class, 'login'])->name('login');

    Route::middleware(['auth:sanctum', 'customer'])->group(function () {
        Route::post('logout', [CustomerController::class, 'logout'])->name('logout');
        Route::get('profile', [CustomerController::class, 'profile'])->name('profile');
        Route::apiResource('orders', OrderController::class);
    });
});

Route::apiResource('categories', CategoryController::class);
Route::apiResource('products', ProductController::class);
Route::get('products/search', [ProductController::class, 'search']);

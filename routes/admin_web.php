<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminWeb\AdminAuthController;
use App\Http\Controllers\AdminWeb\DashboardController;
use App\Http\Controllers\AdminWeb\BannersController;
use App\Http\Controllers\AdminWeb\CategoriesController;
use App\Http\Controllers\AdminWeb\WarehousesController;
use App\Http\Controllers\AdminWeb\MedicinesController;
use App\Http\Controllers\AdminWeb\OrdersController;
use App\Http\Controllers\AdminWeb\PharmaciesController;

Route::get('/', fn() => redirect()->route('admin.login'));

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'show'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::middleware(['auth', 'is_admin'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/banners', [BannersController::class, 'index'])->name('banners.index');
        Route::get('/banners/create', [BannersController::class, 'create'])->name('banners.create');
        Route::post('/banners', [BannersController::class, 'store'])->name('banners.store');
        Route::get('/banners/{banner}/edit', [BannersController::class, 'edit'])->name('banners.edit');
        Route::put('/banners/{banner}', [BannersController::class, 'update'])->name('banners.update');
        Route::delete('/banners/{banner}', [BannersController::class, 'destroy'])->name('banners.destroy');

        Route::get('/categories', [CategoriesController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [CategoriesController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoriesController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [CategoriesController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [CategoriesController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoriesController::class, 'destroy'])->name('categories.destroy');

        Route::get('/warehouses', [WarehousesController::class, 'index'])->name('warehouses.index');
        Route::get('/warehouses/create', [WarehousesController::class, 'create'])->name('warehouses.create');
        Route::post('/warehouses', [WarehousesController::class, 'store'])->name('warehouses.store');
        Route::get('/warehouses/{warehouse}/edit', [WarehousesController::class, 'edit'])->name('warehouses.edit');
        Route::put('/warehouses/{warehouse}', [WarehousesController::class, 'update'])->name('warehouses.update');
        Route::delete('/warehouses/{warehouse}', [WarehousesController::class, 'destroy'])->name('warehouses.destroy');

        Route::get('/medicines', [MedicinesController::class, 'index'])->name('medicines.index');
        Route::get('/medicines/create', [MedicinesController::class, 'create'])->name('medicines.create');
        Route::post('/medicines', [MedicinesController::class, 'store'])->name('medicines.store');
        Route::get('/medicines/{medicine}/edit', [MedicinesController::class, 'edit'])->name('medicines.edit');
        Route::put('/medicines/{medicine}', [MedicinesController::class, 'update'])->name('medicines.update');
        Route::delete('/medicines/{medicine}', [MedicinesController::class, 'destroy'])->name('medicines.destroy');

        Route::get('/orders', [OrdersController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrdersController::class, 'show'])->name('orders.show');
        Route::put('/orders/{order}', [OrdersController::class, 'update'])->name('orders.update');

        Route::get('/pharmacies', [PharmaciesController::class, 'index'])->name('pharmacies.index');
        Route::get('/pharmacies/{user}', [PharmaciesController::class, 'show'])->name('pharmacies.show');
        Route::put('/pharmacies/{user}', [PharmaciesController::class, 'update'])->name('pharmacies.update');
    });
});

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



Route::get('/', fn () => redirect()->route('admin.login'));

Route::get('/login', [AdminAuthController::class, 'show'])
    ->name('admin.login')
    ->name('login');
Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

Route::middleware(['auth', 'is_admin'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/banners', [BannersController::class, 'index'])->name('admin.banners.index');
    Route::get('/banners/create', [BannersController::class, 'create'])->name('admin.banners.create');
    Route::post('/banners', [BannersController::class, 'store'])->name('admin.banners.store');
    Route::get('/banners/{banner}/edit', [BannersController::class, 'edit'])->name('admin.banners.edit');
    Route::put('/banners/{banner}', [BannersController::class, 'update'])->name('admin.banners.update');
    Route::delete('/banners/{banner}', [BannersController::class, 'destroy'])->name('admin.banners.destroy');

    Route::get('/categories', [CategoriesController::class, 'index'])->name('admin.categories.index');
    Route::get('/categories/create', [CategoriesController::class, 'create'])->name('admin.categories.create');
    Route::post('/categories', [CategoriesController::class, 'store'])->name('admin.categories.store');
    Route::get('/categories/{category}/edit', [CategoriesController::class, 'edit'])->name('admin.categories.edit');
    Route::put('/categories/{category}', [CategoriesController::class, 'update'])->name('admin.categories.update');
    Route::delete('/categories/{category}', [CategoriesController::class, 'destroy'])->name('admin.categories.destroy');

    Route::get('/warehouses', [WarehousesController::class, 'index'])->name('admin.warehouses.index');
    Route::get('/warehouses/create', [WarehousesController::class, 'create'])->name('admin.warehouses.create');
    Route::post('/warehouses', [WarehousesController::class, 'store'])->name('admin.warehouses.store');
    Route::get('/warehouses/{warehouse}/edit', [WarehousesController::class, 'edit'])->name('admin.warehouses.edit');
    Route::put('/warehouses/{warehouse}', [WarehousesController::class, 'update'])->name('admin.warehouses.update');
    Route::delete('/warehouses/{warehouse}', [WarehousesController::class, 'destroy'])->name('admin.warehouses.destroy');

    Route::get('/medicines', [MedicinesController::class, 'index'])->name('admin.medicines.index');
    Route::get('/medicines/create', [MedicinesController::class, 'create'])->name('admin.medicines.create');
    Route::post('/medicines', [MedicinesController::class, 'store'])->name('admin.medicines.store');
    Route::get('/medicines/{medicine}/edit', [MedicinesController::class, 'edit'])->name('admin.medicines.edit');
    Route::put('/medicines/{medicine}', [MedicinesController::class, 'update'])->name('admin.medicines.update');
    Route::delete('/medicines/{medicine}', [MedicinesController::class, 'destroy'])->name('admin.medicines.destroy');

    Route::get('/orders', [OrdersController::class, 'index'])->name('admin.orders.index');
    Route::get('/orders/{order}', [OrdersController::class, 'show'])->name('admin.orders.show');
    Route::put('/orders/{order}', [OrdersController::class, 'update'])->name('admin.orders.update');

    Route::get('/pharmacies', [PharmaciesController::class, 'index'])->name('admin.pharmacies.index');
    Route::get('/pharmacies/{user}', [PharmaciesController::class, 'show'])->name('admin.pharmacies.show');
    Route::post('/pharmacies/{user}/approve', [PharmaciesController::class, 'approve'])->name('admin.pharmacies.approve');
    Route::post('/pharmacies/{user}/reject', [PharmaciesController::class, 'reject'])->name('admin.pharmacies.reject');
});

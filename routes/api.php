<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\OrdersController;

Route::middleware([\App\Http\Middleware\DecryptGcmRequest::class])->group(function () {

    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/status', [AuthController::class, 'status']);
    Route::post('/auth/resend-verification', [AuthController::class, 'resendVerification']);

    Route::post('/admin/warehouses/create', [AdminController::class, 'createWarehouse']);
    Route::post('/admin/medicines/create', [AdminController::class, 'createMedicine']);
    Route::post('/admin/medicines/update', [AdminController::class, 'updateMedicine']);
    Route::post('/admin/medicines/delete', [AdminController::class, 'deleteMedicine']);

    Route::post('/inventory/warehouses', [InventoryController::class, 'warehouses']);
    Route::post('/inventory/medicines', [InventoryController::class, 'medicines']);
    Route::post('/inventory/shortages', [InventoryController::class, 'shortages']);
    Route::post('/inventory/update-onhand', [InventoryController::class, 'updateOnHand']);
    Route::post('/inventory/set-minstock', [InventoryController::class, 'setMinStock']);

    Route::post('/orders/create', [OrdersController::class, 'create']);
    Route::post('/orders/create-from-shortages', [OrdersController::class, 'createFromShortages']);
    Route::post('/orders/list', [OrdersController::class, 'list']);
    Route::post('/orders/details', [OrdersController::class, 'details']);

});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\OrdersController;
use App\Http\Middleware\DecryptGcmRequest;

Route::post('/auth/register', [AuthController::class, 'register'])->middleware(DecryptGcmRequest::class);
Route::post('/auth/login', [AuthController::class, 'login'])
    ->middleware(\App\Http\Middleware\DecryptGcmRequest::class);

Route::post('/auth/status', [AuthController::class, 'status'])
    ->middleware('auth:sanctum');

Route::post('/auth/resend-verification', [AuthController::class, 'resendVerification'])
    ->middleware('auth:sanctum');

Route::post('/admin/warehouses/create', [AdminController::class, 'createWarehouse'])->middleware(DecryptGcmRequest::class);
Route::post('/admin/medicines/create', [AdminController::class, 'createMedicine'])->middleware(DecryptGcmRequest::class);
Route::post('/admin/medicines/update', [AdminController::class, 'updateMedicine'])->middleware(DecryptGcmRequest::class);
Route::post('/admin/medicines/delete', [AdminController::class, 'deleteMedicine'])->middleware(DecryptGcmRequest::class);


Route::post('/inventory/warehouses', [InventoryController::class, 'warehouses'])
    ->middleware(['auth:sanctum', DecryptGcmRequest::class]);

Route::post('/inventory/medicines', [InventoryController::class, 'medicines'])
    ->middleware(['auth:sanctum', DecryptGcmRequest::class]);

Route::post('/inventory/shortages', [InventoryController::class, 'shortages'])
    ->middleware(['auth:sanctum', DecryptGcmRequest::class]);

Route::post('/inventory/update-onhand', [InventoryController::class, 'updateOnHand'])
    ->middleware(['auth:sanctum', DecryptGcmRequest::class]);

Route::post('/inventory/set-minstock', [InventoryController::class, 'setMinStock'])
    ->middleware(['auth:sanctum', DecryptGcmRequest::class]);


Route::post('/orders/create', [OrdersController::class, 'create'])->middleware(DecryptGcmRequest::class);
Route::post('/orders/create-from-shortages', [OrdersController::class, 'createFromShortages'])->middleware(DecryptGcmRequest::class);
Route::post('/orders/list', [OrdersController::class, 'list'])->middleware(DecryptGcmRequest::class);
Route::post('/orders/details', [OrdersController::class, 'details'])->middleware(DecryptGcmRequest::class);

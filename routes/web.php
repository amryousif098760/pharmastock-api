<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/api/auth/verify-email', [AuthController::class, 'verifyEmailLink']);

Route::get('/', function () {
    return view('welcome');
});

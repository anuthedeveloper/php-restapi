<?php
// routes/v1/api.php

use App\Http\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PaymentController;

use App\Http\Middleware\AuthMiddleware;

// Define the routes
Route::post('/login', [AuthController::class, 'login']);

Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::post('/users', [UserController::class, 'store']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);

Route::post('/payment', [PaymentController::class, 'processPayment']);
Route::post('/webhook', [PaymentController::class, 'handleWebhook']);
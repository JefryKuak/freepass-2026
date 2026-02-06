<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CanteenController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\OwnerOrderController;
use App\Http\Controllers\Api\MenuController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [UserController::class, 'profile']);
    Route::put('/profile', [UserController::class, 'updateProfile']);
});

Route::get('/canteens', [CanteenController::class, 'index']);
Route::get('/canteens/{id}/menus', [CanteenController::class, 'menus']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/orders', [OrderController::class, 'store']);
    Route::post('/orders/{id}/pay', [OrderController::class, 'pay']);
    Route::get('/my-orders', [OrderController::class, 'myOrders']);
    Route::post('/orders/{id}/review', [OrderController::class, 'review']);
});

Route::middleware(['auth:sanctum', 'role:canteen_owner'])->prefix('canteen_owner')->group(function () {
    Route::post('/menus', [MenuController::class, 'store']);
    Route::put('/menus/{id}', [MenuController::class, 'update']);
    Route::delete('/menus/{id}', [MenuController::class, 'destroy']);

    Route::get('/orders/active', [OwnerOrderController::class, 'active']);
    Route::get('/orders/history', [OwnerOrderController::class, 'history']);
    Route::get('/orders/payment-status', [OwnerOrderController::class, 'paymentStatus']);
    Route::put('/orders/{id}/status', [OwnerOrderController::class, 'updateStatus']);
});

<?php

use App\Http\Controllers\Api\BusinessCentral\OrderStatusController;
use App\Http\Controllers\Api\ManagementSystem\AuthController;
use App\Http\Controllers\Api\POS\User\Cart\CartController;
use App\Http\Controllers\Api\POS\User\Orders\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes (token login with Sanctum)
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthController::class, 'apiLogin']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn (Request $request) => $request->user());
    Route::post('/logout', [AuthController::class, 'apiLogout']);

    Route::get('/cart', [CartController::class, 'getCart']);
    Route::post('/cart/add', [CartController::class, 'addToCart']);
    Route::put('/cart/item/{id}', [CartController::class, 'updateQty']);
    Route::delete('/cart/item/{id}', [CartController::class, 'removeItem']);
    Route::delete('/cart/clear', [CartController::class, 'clearCart']);

    Route::post('/checkout', [OrderController::class, 'checkout']);
    Route::get('/orders/history', [OrderController::class, 'history']);
    Route::get('/orders/{order}/bc-status', [OrderStatusController::class, 'show']);
});

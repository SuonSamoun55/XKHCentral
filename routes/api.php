<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ManagementSystemController\AuthController;
use App\Http\Controllers\Api\ManagementSystemController\WebUserController;
use App\Http\Controllers\Api\POSControllers\POSAdminController\ItemPosController;
use App\Http\Controllers\Api\POSControllers\POSUserController\CartController;
use App\Http\Controllers\Api\POSControllers\POSUserController\OrderController;
use App\Http\Controllers\Api\POSControllers\POSUserController\FavoriteController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthController::class, 'apiLogin']);

Route::post('/users', [WebUserController::class, 'store']);
Route::get('/users', [WebUserController::class, 'index']);
Route::get('/bc-customers', [WebUserController::class, 'getBCCustomers']);
Route::post('/items/sync-from-al', [ItemPosController::class, 'syncFromAl']);
Route::get('/items/image/{itemId}', [ItemPosController::class, 'getItemImage']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/cart', [CartController::class, 'getCart']);
    Route::post('/cart/add', [CartController::class, 'addToCart']);
    Route::put('/cart/item/{id}', [CartController::class, 'updateQty']);
    Route::delete('/cart/item/{id}', [CartController::class, 'removeItem']);
    Route::delete('/cart/clear', [CartController::class, 'clearCart']);

    Route::post('/checkout', [OrderController::class, 'checkout']);
    Route::get('/orders/history', [OrderController::class, 'history']);
        Route::get('/favorites', [FavoriteController::class, 'index']);

    Route::post('/favorites/add', [FavoriteController::class, 'addFavorite']);

    Route::delete('/favorites/remove/{id}', [FavoriteController::class, 'removeFavorite']);

});

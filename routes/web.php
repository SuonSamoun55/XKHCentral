<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ManagementSystemController\WebUserController;
use App\Http\Controllers\Api\ManagementSystemController\AuthController;
use App\Http\Controllers\Api\POSControllers\POSAdminController\ItemPosController;
use App\Http\Controllers\Api\ManagementSystemController\DashboardController;
use App\Http\Controllers\Api\ManagementSystemController\DashboardUserController;
use App\Http\Controllers\Api\POSControllers\POSUserController\CartController;
use App\Http\Controllers\Api\POSControllers\POSUserController\POSUserControllerItemList;
use App\Http\Controllers\Api\POSControllers\POSUserController\OrderController;
use App\Http\Controllers\Api\ManagementSystemController\AdminNotificationController;
use App\Http\Controllers\Api\POSControllers\POSAdminController\AdminOrderController;
use App\Http\Controllers\Api\POSControllers\POSUserController\FavoriteController;
use App\Http\Controllers\Api\POSControllers\POSUserController\NotificationController;

// use
// Use DashboardUserController;


Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

    Route::middleware('auth')->group(function () {

    //+++++++++++++++++ Project Management Route+++++++++++++++++++++++++++++
    Route::get('/users', [WebUserController::class, 'index'])->name('users.index');
    // Admins Route
    Route::get('/admin', [DashboardController::class, 'index'])->name('pos.index');
    Route::get('/users/create', [WebUserController::class, 'create'])->name('users.create');
    Route::post('/users/store', [WebUserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}', [WebUserController::class, 'show'])->name('users.show');
    Route::get('/admin/notification', [AdminNotificationController::class, 'index'])->name('admin.notification');    // Users Route
    Route::get('/', [DashboardUserController::class, 'index'])->name('user.index');

    //----------------------------------------------------------------------

    //+++++++++++++++++ POS Route+++++++++++++++++++++++++++++
    // Admins Route
    Route::get('/pos/interface', [ItemPosController::class, 'index'])->name('pos.interface');
    Route::get('/pos/item-detail/{id}', [ItemPosController::class, 'showItem'])->name('pos.item');
    Route::get('/item-image/{id}', [ItemPosController::class, 'getItemImage'])->name('item.image');
    Route::post('/items/sync-from-al', [ItemPosController::class, 'syncFromAl']);
    Route::get('/pos-admin/locations/active', [ItemPosController::class, 'getActiveLocations']);
Route::post('/pos-admin/items/{id}/update-location', [ItemPosController::class, 'updateLocalItemLocation']);


    Route::get('/admin/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::post('/admin/orders/{id}/confirm', [AdminOrderController::class, 'confirm'])->name('admin.orders.confirm');
    Route::post('/admin/orders/{id}/cancel', [AdminOrderController::class, 'cancel'])->name('admin.orders.cancel');
    Route::get('/admin/order-actions', [AdminOrderController::class, 'actionHistory'])->name('admin.orders.actions');


    // Users Route
 Route::get('/pos-system', [POSUserControllerItemList::class, 'getItems'])->name('user.posinterface');
     Route::get('/pos-system/favorites', [FavoriteController::class, 'getFavorites'])->name('user.pos.favorites');
     Route::post('/pos-system/favorite-toggle', [FavoriteController::class, 'toggle'])->name('user.pos.favorite.toggle');

     Route::get('/pos-system/notifications', [NotificationController::class, 'getNotifications'])->name('user.notifications');
     Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])
        ->name('user.notifications.read');
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])
        ->name('user.notifications.markAllRead');
        Route::delete('/delete-selected', [NotificationController::class, 'deleteSelected'])
        ->name('user.notifications.deleteSelected');
        Route::get('user/notifications',[NotificationController::class,'show'])->name('user.notifications.show');
        
    //  Route::get('/pos-system/notifications', [NotificationController::class, 'getNotifications'])->name('user.pos.notifications');
     
     
     //----------------------------------------------------------------------
  Route::get('/pos-system/cart', [CartController::class, 'index'])->name('user.pos.cart');
Route::get('/pos-system/cart/data', [CartController::class, 'getCart'])->name('user.pos.cart.data');
Route::post('/pos-system/cart/add', [CartController::class, 'addToCart'])->name('user.pos.cart.add');
Route::put('/pos-system/cart/update/{id}', [CartController::class, 'updateQty'])->name('user.pos.cart.update');
Route::delete('/pos-system/cart/remove/{id}', [CartController::class, 'removeItem'])->name('user.pos.cart.remove');
Route::delete('/pos-system/cart/clear', [CartController::class, 'clearCart'])->name('user.pos.cart.clear');
Route::post('/pos-system/checkout', [OrderController::class, 'checkout'])->name('user.pos.checkout');
Route::get('/pos-system/order-history', [OrderController::class, 'history'])->name('user.pos.order.history');

Route::get('/favorites', [FavoriteController::class, 'index']);
});
// Route::middleware('auth')->prefix('users')->name('users.')->group(function () {
//     Route::get('/', [WebUserController::class, 'index'])->name('index');
//     Route::get('/sync', [WebUserController::class, 'syncBCCustomers'])->name('sync');

//     Route::get('/create/{id}', [WebUserController::class, 'create'])->name('create');
//     Route::post('/store/{id}', [WebUserController::class, 'store'])->name('store');

//     Route::get('/show/{id}', [WebUserController::class, 'show'])->name('show');
//     Route::get('/edit/{id}', [WebUserController::class, 'edit'])->name('edit');
//     Route::put('/update/{id}', [WebUserController::class, 'update'])->name('update');

//     Route::delete('/destroy/{id}', [WebUserController::class, 'destroy'])->name('destroy');
//     Route::post('/delete-selected', [WebUserController::class, 'deleteSelected'])->name('deleteSelected');

// });
// 🔓 PUBLIC (no auth)
Route::prefix('users')->name('users.')->group(function () {

    Route::get('/', [WebUserController::class, 'index'])->name('index');

    // ✅ FIX: ADD THIS BACK
    Route::get('/sync', [WebUserController::class, 'syncBCCustomers'])->name('sync');

    Route::get('/create/{id}', [WebUserController::class, 'create'])->name('create');
    Route::post('/store/{id}', [WebUserController::class, 'store'])->name('store');

    Route::get('/show/{id}', [WebUserController::class, 'show'])->name('show');
    Route::get('/edit/{id}', [WebUserController::class, 'edit'])->name('edit');
    Route::put('/update/{id}', [WebUserController::class, 'update'])->name('update');

    Route::delete('/destroy/{id}', [WebUserController::class, 'destroy'])->name('destroy');
    Route::post('/delete-selected', [WebUserController::class, 'deleteSelected'])->name('deleteSelected');

});
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'getNotifications'])
        ->name('notifications.index');

    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])
        ->name('notifications.read');

    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])
        ->name('notifications.read.all');

    Route::delete('/notifications/delete-selected', [NotificationController::class, 'deleteSelected'])
        ->name('notifications.delete.selected');
});

// Route::prefix('admin-notifications')->name('admin.notifications.')->group(function () {
//     Route::get('/', [AdminNotificationController::class, 'index'])->name('index');
//     Route::post('/store', [AdminNotificationController::class, 'store'])->name('store');
//     Route::post('/read/{id}', [AdminNotificationController::class, 'markAsRead'])->name('read');
//     Route::delete('/destroy/{id}', [AdminNotificationController::class, 'destroy'])->name('destroy');
// });


Route::prefix('admin/notification')->name('admin.notifications.')->group(function () {
    Route::get('/', [AdminNotificationController::class, 'index'])->name('index');
    Route::post('/store', [AdminNotificationController::class, 'store'])->name('store');
    Route::post('/read/{id}', [AdminNotificationController::class, 'markAsRead'])->name('read');
    Route::post('/read-all', [AdminNotificationController::class, 'markAllAsRead'])->name('read.all');
    Route::delete('/delete-selected', [AdminNotificationController::class, 'deleteSelected'])->name('delete.selected');
    Route::delete('/destroy/{id}', [AdminNotificationController::class, 'destroy'])->name('destroy');


});

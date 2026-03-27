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
use App\Http\Controllers\Api\ManagementSystemController\RoleController;
use App\Http\Controllers\Api\ManagementSystemController\PermissionController;
use App\Http\Controllers\Api\ManagementSystemController\CompanyController;
use App\Http\Controllers\Api\ManagementSystemController\CompanySelectionController;
use App\Http\Controllers\Api\POSControllers\POSUserController\NotificationController;
// use Illuminate\Support\Facades\Route;

// use
// Use DashboardUserController;

// ================= ROLE MANAGEMENT =================
// Route::get('/users', [WebUserController::class, 'index'])->name('users.index');
// Route::get('/users/sync-bc', [WebUserController::class, 'syncBCCustomers'])->name('users.sync');
// Route::get('/users/create/{id}', [WebUserController::class, 'create'])->name('users.create');
// Route::post('/users/store/{id}', [WebUserController::class, 'store'])->name('users.store');
// Route::get('/users/{id}', [WebUserController::class, 'show'])->name('users.show');

Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
Route::get('/companies/create', [CompanyController::class, 'create'])->name('companies.create');
Route::post('/companies', [CompanyController::class, 'store'])->name('companies.store');
Route::get('/companies/{id}/edit', [CompanyController::class, 'edit'])->name('companies.edit');
Route::put('/companies/{id}', [CompanyController::class, 'update'])->name('companies.update');
Route::delete('/companies/{id}', [CompanyController::class, 'destroy'])->name('companies.destroy');

// Route::post('/companies/select', [CompanySelectionController::class, 'select'])->name('companies.select');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

    Route::middleware('auth')->group(function () {

    //+++++++++++++++++ Project Management Route+++++++++++++++++++++++++++++
    // Admins Route
    Route::get('/admin', [DashboardController::class, 'index'])->name('pos.index');
//    Route::get('/users', [WebUserController::class, 'index'])->name('users.index');
    Route::get('/admin/notification', [AdminNotificationController::class, 'index'])->name('admin.notification');    // Users Route
    Route::get('/', [DashboardUserController::class, 'index'])->name('user.index');
Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
Route::get('/roles/{id}/edit', [RoleController::class, 'edit'])->name('roles.edit');
Route::put('/roles/{id}', [RoleController::class, 'update'])->name('roles.update');
Route::delete('/roles/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');
// ================= PERMISSION =================
Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
Route::get('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
Route::get('/permissions/{id}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
Route::put('/permissions/{id}', [PermissionController::class, 'update'])->name('permissions.update');
Route::delete('/permissions/{id}', [PermissionController::class, 'destroy'])->name('permissions.destroy');

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
     Route::post('/pos-system/favorite-toggle', [FavoriteController::class, 'toggle'])
    ->name('user.pos.favorite.toggle');
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
Route::middleware('auth')->prefix('users')->name('users.')->group(function () {
    Route::get('/', [WebUserController::class, 'index'])->name('index');
    Route::get('/sync', [WebUserController::class, 'syncBCCustomers'])->name('sync');

    Route::get('/create/{id}', [WebUserController::class, 'create'])->name('create');
    Route::post('/store/{id}', [WebUserController::class, 'store'])->name('store');

    Route::get('/show/{id}', [WebUserController::class, 'show'])->name('show');
    Route::get('/edit/{id}', [WebUserController::class, 'edit'])->name('edit');
    Route::put('/update/{id}', [WebUserController::class, 'update'])->name('update');

    Route::delete('/destroy/{id}', [WebUserController::class, 'destroy'])->name('destroy');
    Route::post('/delete-selected', [WebUserController::class, 'deleteSelected'])->name('deleteSelected');
});

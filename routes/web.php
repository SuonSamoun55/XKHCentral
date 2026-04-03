<?php

use Illuminate\Support\Facades\Route;
use App\Models\MagamentSystemModel\User;
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
use App\Http\Controllers\Api\POSControllers\POSUserController\UserProfileController;
use App\Http\Controllers\Api\POSControllers\POSUserController\HistoryController;
use App\Http\Controllers\Api\ManagementSystemController\CompanyController;

// ================= AUTH =================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// ================= HEARTBEAT =================
Route::middleware(['auth'])->post('/heartbeat', function () {

    /** @var User|null $user */
    $user = auth()->user();

    if ($user instanceof User) {
        $user->last_seen_at = now();
        $user->save();
    }

    return response()->json([
        'success' => true,
        'time' => now()->toDateTimeString(),
    ]);

})->name('heartbeat');
// ================= AUTHENTICATED ROUTES =================
Route::middleware(['auth', 'last.seen'])->group(function () {

    // ---------- Dashboard ----------
    Route::get('/admin', [DashboardController::class, 'index'])->name('pos.index');
    Route::get('/', [DashboardUserController::class, 'index'])->name('user.index');

    // ---------- Users ----------

    // ---------- Admin Notification ----------
    Route::get('/admin/notification', [AdminNotificationController::class, 'index'])->name('admin.notification');

    // ---------- POS Admin ----------
    Route::get('/pos/interface', [ItemPosController::class, 'index'])->name('pos.interface');
    Route::get('/pos/item-detail/{id}', [ItemPosController::class, 'showItem'])->name('pos.item');
    Route::get('/item-image/{id}', [ItemPosController::class, 'getItemImage'])->name('item.image');
    Route::post('/items/sync-from-al', [ItemPosController::class, 'syncFromAl']);
    Route::get('/pos-admin/locations/active', [ItemPosController::class, 'getActiveLocations']);
    Route::post('/pos-admin/items/{id}/update-location', [ItemPosController::class, 'updateLocalItemLocation']);
    Route::get('/pos/items/{id}', [ItemPosController::class, 'detail'])->name('pos.items.detail');
    Route::get('/pos/items/{id}/json', [ItemPosController::class, 'showItem'])->name('pos.items.json');

    Route::get('/admin/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::post('/admin/orders/{id}/confirm', [AdminOrderController::class, 'confirm'])->name('admin.orders.confirm');
    Route::post('/admin/orders/{id}/cancel', [AdminOrderController::class, 'cancel'])->name('admin.orders.cancel');
    Route::get('/admin/order-actions', [AdminOrderController::class, 'actionHistory'])->name('admin.orders.actions');
    Route::get('/admin/orders/{id}', [AdminOrderController::class, 'show'])->name('admin.orders.show');

    // ---------- POS User ----------
    Route::get('/pos-system', [POSUserControllerItemList::class, 'getItems'])->name('user.posinterface');
    Route::get('/pos-system/favorites', [FavoriteController::class, 'getFavorites'])->name('user.pos.favorites');
    Route::post('/pos-system/favorite-toggle', [FavoriteController::class, 'toggle'])->name('user.pos.favorite.toggle');

    Route::get('/pos-system/notifications', [NotificationController::class, 'getNotifications'])->name('user.notifications');
    Route::get('/pos-system/notifications/unread', [NotificationController::class, 'unreadNotifications'])->name('user.notifications.unread');
    Route::get('/pos-system/notifications/{id}', [NotificationController::class, 'show'])->name('user.notifications.show');
    Route::post('/pos-system/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('user.notifications.read');
    Route::post('/pos-system/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('user.notifications.markAllRead');
    Route::delete('/pos-system/notifications/delete-selected', [NotificationController::class, 'deleteSelected'])->name('user.notifications.deleteSelected');

    Route::get('/profile', [UserProfileController::class, 'index'])->name('profile');
    Route::put('/profile/update', [UserProfileController::class, 'update'])->name('profile.update');

    Route::get('/pos-system/order/download/{id}', [HistoryController::class, 'downloadInvoice'])->name('user.pos.order.download');
    Route::get('/pos-system/order-history', [HistoryController::class, 'history'])->name('user.pos.order.history');

    Route::get('/pos-system/cart', [CartController::class, 'index'])->name('user.pos.cart');
    Route::get('/pos-system/cart/data', [CartController::class, 'getCart'])->name('user.pos.cart.data');
    Route::post('/pos-system/cart/add', [CartController::class, 'addToCart'])->name('user.pos.cart.add');
    Route::put('/pos-system/cart/update/{id}', [CartController::class, 'updateQty'])->name('user.pos.cart.update');
    Route::delete('/pos-system/cart/remove/{id}', [CartController::class, 'removeItem'])->name('user.pos.cart.remove');
    Route::delete('/pos-system/cart/clear', [CartController::class, 'clearCart'])->name('user.pos.cart.clear');
    Route::post('/pos-system/checkout', [OrderController::class, 'checkout'])->name('user.pos.checkout');

    Route::get('/favorites', [FavoriteController::class, 'index']);

    // ---------- Notifications ----------
    Route::get('/notifications', [NotificationController::class, 'getNotifications'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read.all');
    Route::delete('/notifications/delete-selected', [NotificationController::class, 'deleteSelected'])->name('notifications.delete.selected');

    // ---------- Admin Notifications ----------
    Route::prefix('admin/notification')->name('admin.notifications.')->group(function () {
        Route::get('/', [AdminNotificationController::class, 'index'])->name('index');
        Route::post('/store', [AdminNotificationController::class, 'store'])->name('store');
        Route::post('/read/{id}', [AdminNotificationController::class, 'markAsRead'])->name('read');
        Route::post('/read-all', [AdminNotificationController::class, 'markAllAsRead'])->name('read.all');
        Route::delete('/delete-selected', [AdminNotificationController::class, 'deleteSelected'])->name('delete.selected');
        Route::delete('/destroy/{id}', [AdminNotificationController::class, 'destroy'])->name('destroy');
    });

    // ---------- Companies ----------
    Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
    Route::get('/companies/create', [CompanyController::class, 'create'])->name('companies.create');
    Route::post('/companies', [CompanyController::class, 'store'])->name('companies.store');
    Route::get('/companies/{id}/edit', [CompanyController::class, 'edit'])->name('companies.edit');
    Route::put('/companies/{id}', [CompanyController::class, 'update'])->name('companies.update');
    Route::delete('/companies/{id}', [CompanyController::class, 'destroy'])->name('companies.destroy');
});
 Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [WebUserController::class, 'index'])->name('index');
        Route::get('/bc-image/{bcId}', [WebUserController::class, 'getBCImage'])->name('bc-image');
        Route::get('/sync', [WebUserController::class, 'syncBCCustomers'])->name('sync');
        Route::get('/create/{id}', [WebUserController::class, 'create'])->name('create');
        Route::post('/store/{id}', [WebUserController::class, 'store'])->name('store');
        Route::get('/show/{id}', [WebUserController::class, 'show'])->name('show');
        Route::get('/edit/{id}', [WebUserController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [WebUserController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [WebUserController::class, 'destroy'])->name('destroy');
        Route::post('/delete-selected', [WebUserController::class, 'deleteSelected'])->name('deleteSelected');
        Route::get('/data', [WebUserController::class, 'getUsers'])->name('data');
    });

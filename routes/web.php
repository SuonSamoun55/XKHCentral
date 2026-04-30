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
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\POSControllers\POSAdminController\AdminOrderController;
use App\Http\Controllers\Api\POSControllers\POSUserController\FavoriteController;
use App\Http\Controllers\Api\POSControllers\POSUserController\NotificationController;
use App\Http\Controllers\Api\POSControllers\POSUserController\UserProfileController;
use App\Http\Controllers\Api\POSControllers\POSUserController\HistoryController;
use App\Http\Controllers\Api\ManagementSystemController\CompanyController;
use App\Http\Controllers\Api\POSControllers\POSAdminController\StoreManagementController;
use App\Http\Controllers\Api\POSControllers\POSAdminController\DiscountController;
use App\Http\Controllers\Api\POSControllers\POSAdminController\UserController;
use App\Http\Controllers\Api\POSControllers\POSAdminController\AdminProfileController;



// Route::get('/store-management', [StoreManagementController::class, 'index'])->name('store.management.index');
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
Route::get('/store-management/data', [StoreManagementController::class, 'getData'])->name('store.management.data');
Route::get('/store-management', [StoreManagementController::class, 'index'])->name('store.management.index');
Route::get('/store-management/tracking', [StoreManagementController::class, 'tracking'])->name('store.management.tracking');
Route::get('/store-management/products/{id}/detail', [StoreManagementController::class, 'productDetail'])->name('store.management.products.detail');
// Route::get('/store-management/data', [StoreManagementController::class, 'getData'])->name('store.management.data');
Route::post('/store-management/products/{id}/toggle', [StoreManagementController::class, 'toggleProduct'])->name('store.management.products.toggle');
Route::post('/store-management/categories/{code}/toggle', [StoreManagementController::class, 'toggleCategory'])->name('store.management.categories.toggle');
Route::post('/store-management/products/bulk-update', [StoreManagementController::class, 'bulkUpdateProducts'])->name('store.management.products.bulkUpdate');
Route::post('/store-management/categories/bulk-update', [StoreManagementController::class, 'bulkUpdateCategories'])->name('store.management.categories.bulkUpdate');
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
    Route::post('/pos/cart/add', [ItemPosController::class, 'addItemToCart'])->name('pos.cart.add');

    Route::get('/admin/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::post('/admin/orders/{id}/confirm', [AdminOrderController::class, 'confirm'])->name('admin.orders.confirm');
    Route::post('/admin/orders/{id}/cancel', [AdminOrderController::class, 'cancel'])->name('admin.orders.cancel');
    Route::get('/admin/order-actions', [AdminOrderController::class, 'actionHistory'])->name('admin.orders.actions');
    Route::get('/admin/orders/{id}', [AdminOrderController::class, 'show'])->name('admin.orders.show');

    ////////USER CONTROLLER
    Route::prefix('admin')->group(function () {
    Route::get('/users/{id}', [App\Http\Controllers\Api\POSControllers\POSAdminController\UserController::class, 'show'])->name('admin.users.show');
});
    // ---------- POS User ----------
    Route::get('/pos-system', [POSUserControllerItemList::class, 'getItems'])->name('user.posinterface');
    Route::get('/pos-system/categories', [POSUserControllerItemList::class, 'mobileCategories'])->name('user.pos.categories');
    Route::get('/pos-system/categories/{category}', [POSUserControllerItemList::class, 'mobileCategoryProducts'])->name('user.pos.categories.products');
    Route::get('/pos-system/products', [POSUserControllerItemList::class, 'mobileProducts'])->name('user.pos.products');
    Route::get('/pos/products/filter', [POSUserControllerItemList::class, 'filter'])->name('user.pos.products.filter');

    Route::get('/pos-system/product/{id}', 
    [POSUserControllerItemList::class, 'showProduct'])->name('user.pos.product.detail');
    Route::get('/pos-system/favorites', [FavoriteController::class, 'getFavorites'])->name('user.pos.favorites');
    Route::post('/pos-system/favorite-toggle', [FavoriteController::class, 'toggle'])->name('user.pos.favorite.toggle');

    Route::get('/pos-system/notifications', [NotificationController::class, 'getNotifications'])->name('user.notifications');
    Route::get('/pos-system/chat', [ChatController::class, 'userIndex'])->name('user.chat.index');
    Route::post('/pos-system/chat/send', [ChatController::class, 'userSend'])->name('user.chat.send');
    Route::get('/pos-system/chat/messages', [ChatController::class, 'userMessages'])->name('user.chat.messages');
    Route::get('/pos-system/notifications/unread', [NotificationController::class, 'unreadNotifications'])->name('user.notifications.unread');
    Route::get('/pos-system/notifications/{id}', [NotificationController::class, 'show'])->name('user.notifications.show');
    Route::post('/pos-system/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('user.notifications.read');
    Route::post('/pos-system/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('user.notifications.markAllRead');
    Route::delete('/pos-system/notifications/delete-selected', [NotificationController::class, 'deleteSelected'])->name('user.notifications.deleteSelected');

    Route::get('/profile', [UserProfileController::class, 'index'])->name('profile');
    Route::put('/profile/update', [UserProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [UserProfileController::class, 'showChangePasswordForm'])->name('user.password.change');
    Route::put('/profile/change-password', [UserProfileController::class, 'updatePassword'])->name('user.password.update');

    Route::get('/pos-system/order/download/{id}', [HistoryController::class, 'downloadInvoice'])->name('user.pos.order.download');
    Route::get('/pos-system/order/{id}', [HistoryController::class, 'show'])->name('user.pos.order.show');
    Route::post('/pos-system/order/{id}/cancel', [HistoryController::class, 'cancel'])->name('user.pos.order.cancel');
    Route::get('/pos-system/order-history', [HistoryController::class, 'history'])->name('user.pos.order.history');

    Route::get('/pos-system/cart', [CartController::class, 'index'])->name('user.pos.cart');
    Route::get('/pos-system/cart/data', [CartController::class, 'getCart'])->name('user.pos.cart.data');
    Route::post('/pos-system/cart/add', [CartController::class, 'addToCart'])->name('user.pos.cart.add');
    Route::put('/pos-system/cart/update/{id}', [CartController::class, 'updateQty'])->name('user.pos.cart.update');
    Route::delete('/pos-system/cart/remove/{id}', [CartController::class, 'removeItem'])->name('user.pos.cart.remove');
    Route::delete('/pos-system/cart/clear', [CartController::class, 'clearCart'])->name('user.pos.cart.clear');
    Route::post('/pos-system/checkout', [OrderController::class, 'checkout'])->name('user.pos.checkout');

    Route::get('/favorites', [FavoriteController::class, 'index']);

    // ---------- Admin Notifications (Canonical) ----------
    Route::prefix('admin/notifications')->name('admin.notifications.')->group(function () {
        Route::get('/', [AdminNotificationController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminNotificationController::class, 'show'])->name('show');
        Route::post('/store', [AdminNotificationController::class, 'store'])->name('store');
        Route::post('/read/{id}', [AdminNotificationController::class, 'markAsRead'])->name('read');
        Route::post('/read-all', [AdminNotificationController::class, 'markAllAsRead'])->name('read.all');
        Route::delete('/delete-selected', [AdminNotificationController::class, 'deleteSelected'])->name('delete.selected');
        Route::delete('/destroy/{id}', [AdminNotificationController::class, 'destroy'])->name('destroy');
        Route::get('/ajax/search-customers', [AdminNotificationController::class, 'searchCustomers'])->name('ajax.search.customers');
        Route::get('/ajax/latest', [AdminNotificationController::class, 'latestNotifications'])->name('ajax.latest');
    });

    // ---------- Admin Notifications (Legacy /admin/notification path) ----------
    Route::prefix('admin/notification')->group(function () {
        Route::get('/', [AdminNotificationController::class, 'index']);
        Route::post('/store', [AdminNotificationController::class, 'store']);
        Route::post('/read/{id}', [AdminNotificationController::class, 'markAsRead']);
        Route::post('/read-all', [AdminNotificationController::class, 'markAllAsRead']);
        Route::delete('/delete-selected', [AdminNotificationController::class, 'deleteSelected']);
        Route::delete('/destroy/{id}', [AdminNotificationController::class, 'destroy']);
    });
    Route::get('/admin/notification/chat', [ChatController::class, 'adminIndex'])->name('admin.chat.index');
    Route::post('/admin/notification/chat/send', [ChatController::class, 'adminSend'])->name('admin.chat.send');
    Route::get('/admin/notification/chat/messages', [ChatController::class, 'adminMessages'])->name('admin.chat.messages');
    ///-----------admin settings----------
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/profile', [AdminProfileController::class, 'index'])->name('profile');
        Route::put('/profile/update', [AdminProfileController::class, 'update'])->name('profile.update');
        Route::get('/change-password', [AdminProfileController::class, 'showChangePasswordForm'])->name('password.change');
        Route::put('/change-password', [AdminProfileController::class, 'updatePassword'])->name('password.update');
    });

    // ---------- Companies ----------
    Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
    Route::get('/companies/create', [CompanyController::class, 'create'])->name('companies.create');
    Route::post('/companies', [CompanyController::class, 'store'])->name('companies.store');
    Route::get('/companies/{id}/edit', [CompanyController::class, 'edit'])->name('companies.edit');
    Route::put('/companies/{id}', [CompanyController::class, 'update'])->name('companies.update');
    Route::get('/companies/{id}/api-setup', [CompanyController::class, 'apiSetup'])->name('companies.api.setup');
    Route::put('/companies/{id}/api-setup', [CompanyController::class, 'updateApiSetup'])->name('companies.api.setup.update');
    Route::delete('/companies/{id}', [CompanyController::class, 'destroy'])->name('companies.destroy');

});
Route::middleware(['auth', 'last.seen'])->prefix('users')->name('users.')->group(function () {
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
Route::middleware(['auth', 'last.seen'])->group(function () {
    Route::get('/discounts', [DiscountController::class, 'index'])->name('discounts.index');
    Route::get('/discounts/create', [DiscountController::class, 'create'])->name('discounts.create');
    Route::post('/discounts', [DiscountController::class, 'store'])->name('discounts.store');
    Route::get('/discounts/{id}/edit', [DiscountController::class, 'edit'])->name('discounts.edit');
    Route::put('/discounts/{id}', [DiscountController::class, 'update'])->name('discounts.update');
    Route::delete('/discounts/{id}', [DiscountController::class, 'destroy'])->name('discounts.destroy');
});

<?php
use Illuminate\Support\Facades\Route;
use App\Models\ManagementSystem\User;
use App\Http\Controllers\Api\ManagementSystem\WebUserController;
use App\Http\Controllers\Api\ManagementSystem\AuthController;
use App\Http\Controllers\Api\POS\Admin\Items\ItemPosController;
use App\Http\Controllers\Api\ManagementSystem\DashboardController;
use App\Http\Controllers\Api\POS\User\Daskboard\DashboardUserController;
use App\Http\Controllers\Api\POS\User\Cart\CartController;
use App\Http\Controllers\Api\POS\User\Products\ItemListController;
use App\Http\Controllers\Api\POS\User\Orders\OrderController;
use App\Http\Controllers\Api\ManagementSystem\AdminNotificationController;
use App\Http\Controllers\Api\Communication\ChatController;
use App\Http\Controllers\Api\POS\Admin\Orders\AdminOrderController;
use App\Http\Controllers\Api\POS\User\Favorites\FavoriteController;
use App\Http\Controllers\Api\POS\User\Notifications\NotificationController;
use App\Http\Controllers\Api\POS\User\Profile\UserProfileController;
use App\Http\Controllers\Api\POS\User\Orders\HistoryController;
use App\Http\Controllers\Api\ManagementSystem\CompanyController;
use App\Http\Controllers\Api\POS\Admin\StoreManagement\StoreManagementController;
use App\Http\Controllers\Api\POS\Admin\Discounts\DiscountController;
use App\Http\Controllers\Api\POS\Admin\TaxGroups\TaxGroupController;
use App\Http\Controllers\Api\POS\Admin\Profile\AdminProfileController;
use App\Http\Controllers\Api\POS\User\Legal\PolicyController;
use App\Http\Controllers\Api\BusinessCentral\OrderStatusController;
use App\Http\Controllers\Api\POS\Admin\Items\ItemVariantPosController;
use App\Http\Controllers\Api\ManagementSystem\RoleController;
use App\Http\Controllers\Api\ManagementSystem\PermissionController;
use App\Http\Controllers\Api\ManagementSystem\StaffController;

Route::view('/test-ui', 'POSViews.POSUserViews.Testing.test-ui')
    ->name('user.pos.test_ui');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

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
Route::middleware('permission:store_management')->group(function () {
Route::get('/store-management/data', [StoreManagementController::class, 'getData'])->name('store.management.data');
Route::get('/store-management', [StoreManagementController::class, 'index'])->name('store.management.index');
Route::get('/store-management/tracking', [StoreManagementController::class, 'tracking'])->name('store.management.tracking');
Route::get('/store-management/products/{id}/detail', [StoreManagementController::class, 'productDetail'])->name('store.management.products.detail');
// Route::get('/store-management/data', [StoreManagementController::class, 'getData'])->name('store.management.data');
Route::post('/store-management/products/{id}/toggle', [StoreManagementController::class, 'toggleProduct'])->name('store.management.products.toggle');
Route::post('/store-management/categories/{code}/toggle', [StoreManagementController::class, 'toggleCategory'])->name('store.management.categories.toggle');
Route::post('/store-management/products/bulk-update', [StoreManagementController::class, 'bulkUpdateProducts'])->name('store.management.products.bulkUpdate');
Route::post('/store-management/categories/bulk-update', [StoreManagementController::class, 'bulkUpdateCategories'])->name('store.management.categories.bulkUpdate');
Route::get('/store/management/products/{id}/images', [StoreManagementController::class, 'editImages'])
    ->name('store.management.product.images');
Route::post('/store/management/products/{id}/image', [StoreManagementController::class, 'uploadMainImage'])
    ->name('store.management.product.image.upload');
Route::post('/store/management/products/{id}/mark-updated', [StoreManagementController::class, 'markUpdated'])
    ->name('store.management.product.markUpdated');
});

    // ---------- Dashboard ----------
    Route::middleware('permission:dashboard')->group(function () {
        Route::get('/admin', [DashboardController::class, 'index'])->name('pos.index');
        Route::get('/admin/dashboard/report-chart', [DashboardController::class, 'reportChart'])->name('admin.dashboard.report-chart');
        Route::get('/admin/dashboard/top-products', [DashboardController::class, 'topProductsData'])->name('admin.dashboard.top-products');
        Route::get('/admin/dashboard/overview-stats', [DashboardController::class, 'overviewStats'])->name('admin.dashboard.overview-stats');
    });
    Route::middleware('permission:home')->group(function () {
        Route::get('/', [DashboardUserController::class, 'index'])->name('user.index');
    });

    // ---------- Users ----------

    // ---------- Admin Notification ----------
    Route::middleware('permission:notifications')->group(function () {
        Route::get('/admin/notification', [AdminNotificationController::class, 'index'])->name('admin.notification');
    });


    Route::get('/item-image/{id}', [ItemPosController::class, 'getItemImage'])->name('item.image');

    // ---------- POS Admin ----------
    Route::middleware('permission:pos')->group(function () {
        Route::get('/pos/interface', [ItemPosController::class, 'index'])->name('pos.interface');
        Route::get('/pos/item-detail/{id}', [ItemPosController::class, 'showItem'])->name('pos.item');
        Route::post('/items/sync-from-al', [ItemPosController::class, 'syncFromAl']);
        Route::get('/pos/items/{id}', [ItemPosController::class, 'detail'])->name('pos.items.detail');
        Route::get('/pos/items/{id}/json', [ItemPosController::class, 'showItem'])->name('pos.items.json');
    });

    Route::middleware('permission:orders')->group(function () {
        Route::get('/admin/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
        Route::post('/admin/orders/{id}/confirm', [AdminOrderController::class, 'confirm'])->name('admin.orders.confirm');
        Route::post('/admin/orders/{id}/cancel', [AdminOrderController::class, 'cancel'])->name('admin.orders.cancel');
        Route::get('/admin/order-actions', [AdminOrderController::class, 'actionHistory'])->name('admin.orders.actions');
        Route::get('/admin/orders/{id}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
        Route::get('/admin/orders/{id}/invoice', [AdminOrderController::class, 'downloadInvoice'])->name('admin.orders.invoice');
    });



    // ---------- POS User ----------
    // Every "User Side" page now actually enforces its Page List permission
    // (previously reference-only) — a role/user gets nothing here until it's
    // explicitly checked under Roles, same rule as every admin page.
    Route::middleware('permission:storefront')->group(function () {
        Route::get('/pos-system', [ItemListController::class, 'getItems'])->name('user.posinterface');
        Route::get('/pos-system/product/{id}', [ItemListController::class, 'showProduct'])->name('user.pos.product.detail');
        Route::get('/pos/products/filter', [ItemListController::class, 'filter'])->name('user.pos.products.filter');
    });

    Route::middleware('permission:favorites')->group(function () {
        Route::get('/pos-system/favorites', [FavoriteController::class, 'getFavorites'])->name('user.pos.favorites');
        Route::post('/pos-system/favorite-toggle', [FavoriteController::class, 'toggle'])->name('user.pos.favorite.toggle');
    });

    Route::middleware('permission:user_notifications')->group(function () {
        Route::get('/pos-system/notifications', [NotificationController::class, 'getNotifications'])->name('user.notifications');
        Route::get('/pos-system/notifications/unread', [NotificationController::class, 'unreadNotifications'])->name('user.notifications.unread');
        Route::get('/pos-system/notifications/{id}', [NotificationController::class, 'show'])->name('user.notifications.show');
        Route::get('/pos-system/notifications/{id}/items', [NotificationController::class, 'getNotificationItems'])->name('user.notifications.items');
        Route::post('/pos-system/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('user.notifications.read');
        Route::post('/pos-system/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('user.notifications.markAllRead');
        Route::delete('/pos-system/notifications/delete-selected', [NotificationController::class, 'deleteSelected'])->name('user.notifications.deleteSelected');
    });

    Route::middleware('permission:chat')->group(function () {
        Route::get('/pos-system/chat', [ChatController::class, 'userIndex'])->name('user.chat.index');
        Route::post('/pos-system/chat/send', [ChatController::class, 'userSend'])->name('user.chat.send');
        Route::get('/pos-system/chat/messages', [ChatController::class, 'userMessages'])->name('user.chat.messages');
    });

    Route::middleware('permission:store_management')->group(function () {
        Route::get('/store/management/variants', [ItemVariantPosController::class, 'manage'])
            ->name('store.management.variants');

        Route::get('/items/{itemId}/variants', [ItemVariantPosController::class, 'index']);

        Route::post('/items/variants/{variantId}/image', [ItemVariantPosController::class, 'uploadImage']);
    });

    Route::middleware('permission:profile')->group(function () {
        Route::get('/profile', [UserProfileController::class, 'index'])->name('profile');
        Route::get('/profile/edit', [UserProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/update', [UserProfileController::class, 'update'])->name('profile.update');
        Route::get('/profile/change-password', [UserProfileController::class, 'showChangePasswordForm'])->name('user.password.change');
        Route::put('/profile/change-password', [UserProfileController::class, 'updatePassword'])->name('user.password.update');
    });

    Route::middleware('permission:order_history')->group(function () {
        Route::get('/pos-system/order/download/{id}', [HistoryController::class, 'downloadInvoice'])->name('user.pos.order.download');
        Route::get('/pos-system/order/{order}/bc-status', [OrderStatusController::class, 'show'])->name('user.pos.order.bc-status');
        Route::get('/pos-system/order/{id}', [HistoryController::class, 'show'])->name('user.pos.order.show');
        Route::post('/pos-system/order/{id}/cancel', [HistoryController::class, 'cancel'])->name('user.pos.order.cancel');
        Route::get('/pos-system/order-history', [HistoryController::class, 'history'])->name('user.pos.order.history');
        Route::delete('/orders/delete-multiple', [HistoryController::class, 'deleteMultiple'])->name('user.pos.order.deleteMultiple');
        Route::get('/pos-system/order-detail/{id}', [OrderController::class, 'detail'])->name('user.pos.order.detail');
    });

    Route::middleware('permission:cart')->group(function () {
        Route::get('/pos-system/cart', [CartController::class, 'index'])->name('user.pos.cart');
        Route::get('/pos-system/cart/data', [CartController::class, 'getCart'])->name('user.pos.cart.data');
        Route::post('/pos-system/cart/add', [CartController::class, 'addToCart'])->name('user.pos.cart.add');
        Route::put('/pos-system/cart/update/{id}', [CartController::class, 'updateQty'])->name('user.pos.cart.update');
        Route::delete('/pos-system/cart/remove/{id}', [CartController::class, 'removeItem'])->name('user.pos.cart.remove');
        Route::delete('/pos-system/cart/clear', [CartController::class, 'clearCart'])->name('user.pos.cart.clear');
    });

    Route::middleware('permission:checkout')->group(function () {
        Route::get('/pos-system/checkout', [CartController::class, 'checkout'])->name('user.pos.checkout');
        Route::post('/pos-system/checkout', [OrderController::class, 'checkout'])->name('user.pos.checkout.store');
        Route::get('/pos-system/order-success', [OrderController::class, 'success'])->name('user.pos.checkout.success');
    });



    // ---------- Admin Notifications (Canonical) ----------
    Route::middleware('permission:notifications')->prefix('admin/notifications')->name('admin.notifications.')->group(function () {
        Route::get('/', [AdminNotificationController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminNotificationController::class, 'show'])->name('show');
        Route::post('/store', [AdminNotificationController::class, 'store'])->name('store');
        Route::post('/read/{id}', [AdminNotificationController::class, 'markAsRead'])->name('read');
        Route::post('/read-all', [AdminNotificationController::class, 'markAllAsRead'])->name('read.all');
        Route::post('/read-selected', [AdminNotificationController::class, 'markSelectedAsRead'])->name('read.selected');
        Route::delete('/delete-selected', [AdminNotificationController::class, 'deleteSelected'])->name('delete.selected');
        Route::delete('/destroy/{id}', [AdminNotificationController::class, 'destroy'])->name('destroy');
        Route::get('/ajax/search-customers', [AdminNotificationController::class, 'searchCustomers'])->name('ajax.search.customers');
        Route::get('/ajax/latest', [AdminNotificationController::class, 'latestNotifications'])->name('ajax.latest');
    });

    // ---------- Admin Notifications (Legacy /admin/notification path) ----------
    Route::middleware('permission:notifications')->prefix('admin/notification')->group(function () {
        Route::get('/', [AdminNotificationController::class, 'index']);
        Route::post('/store', [AdminNotificationController::class, 'store']);
        Route::post('/read/{id}', [AdminNotificationController::class, 'markAsRead']);
        Route::post('/read-all', [AdminNotificationController::class, 'markAllAsRead']);
        Route::delete('/delete-selected', [AdminNotificationController::class, 'deleteSelected']);
        Route::delete('/destroy/{id}', [AdminNotificationController::class, 'destroy']);
    });
    // Chat is its own permission, separate from Notifications, so a role
    // (e.g. "Support") can be granted chat access without full Notifications
    // management, or vice versa.
    Route::middleware('permission:admin_chat')->group(function () {
        Route::get('/admin/notification/chat', [ChatController::class, 'adminIndex'])->name('admin.chat.index');
        Route::post('/admin/notification/chat/send', [ChatController::class, 'adminSend'])->name('admin.chat.send');
        Route::get('/admin/notification/chat/messages', [ChatController::class, 'adminMessages'])->name('admin.chat.messages');
    });
    ///-----------admin settings----------
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/profile', [AdminProfileController::class, 'index'])->name('profile');
        Route::put('/profile/update', [AdminProfileController::class, 'update'])->name('profile.update');
        Route::get('/change-password', [AdminProfileController::class, 'showChangePasswordForm'])->name('password.change');
        Route::put('/change-password', [AdminProfileController::class, 'updatePassword'])->name('password.update');
    });

    // ---------- Companies ----------
    Route::middleware(['permission:companies', 'no-company-scope'])->group(function () {
        Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
        Route::get('/companies/create', [CompanyController::class, 'create'])->name('companies.create');
        Route::post('/companies', [CompanyController::class, 'store'])->name('companies.store');
        Route::get('/companies/{id}/edit', [CompanyController::class, 'edit'])->name('companies.edit');
        Route::put('/companies/{id}', [CompanyController::class, 'update'])->name('companies.update');
        Route::get('/companies/{id}/api-setup', [CompanyController::class, 'apiSetup'])->name('companies.api.setup');
        Route::put('/companies/{id}/api-setup', [CompanyController::class, 'updateApiSetup'])->name('companies.api.setup.update');
        Route::delete('/companies/{id}', [CompanyController::class, 'destroy'])->name('companies.destroy');
        Route::post('/companies/{id}/select', [CompanyController::class, 'select'])->name('companies.select');
        Route::post('/companies/clear-selection', [CompanyController::class, 'clearSelection'])->name('companies.clearSelection');
    });

    // ---------- Roles & Page Management ----------
    Route::middleware('permission:roles')->group(function () {
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
        Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
        Route::get('/roles/{id}/edit', [RoleController::class, 'edit'])->name('roles.edit');
        Route::put('/roles/{id}', [RoleController::class, 'update'])->name('roles.update');
        Route::delete('/roles/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');
    });

    Route::middleware('permission:page_management')->group(function () {
        Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
        Route::get('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
        Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
        Route::get('/permissions/{id}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
        Route::put('/permissions/{id}', [PermissionController::class, 'update'])->name('permissions.update');
        Route::delete('/permissions/{id}', [PermissionController::class, 'destroy'])->name('permissions.destroy');
    });

});
Route::middleware(['auth', 'last.seen'])->prefix('users')->name('users.')->group(function () {
        // Not gated by 'permission:users' — bc-image is an avatar proxy hit from
        // customer-facing pages too, and syncOne is self-service (the controller
        // already checks $isOwner-or-$isAdmin), so neither belongs to the admin
        // "Users" page specifically.
        Route::get('/bc-image/{bcId}', [WebUserController::class, 'getBCImage'])->name('bc-image');
        Route::post('/{id}/sync-bc', [WebUserController::class, 'syncSingleCustomer'])->name('syncOne');

        Route::middleware('permission:users')->group(function () {
            Route::get('/', [WebUserController::class, 'index'])->name('index');
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
    });

Route::middleware(['auth', 'last.seen', 'permission:users'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/', [StaffController::class, 'index'])->name('index');
    Route::post('/', [StaffController::class, 'store'])->name('store');
    Route::put('/{id}', [StaffController::class, 'update'])->name('update');
    Route::put('/{id}/password', [StaffController::class, 'updatePassword'])->name('updatePassword');
    Route::delete('/{id}', [StaffController::class, 'destroy'])->name('destroy');
});
Route::middleware(['auth', 'last.seen', 'permission:discounts'])->group(function () {
    Route::get('/discounts', [DiscountController::class, 'index'])->name('discounts.index');
    Route::get('/discounts/create', [DiscountController::class, 'create'])->name('discounts.create');
    Route::post('/discounts', [DiscountController::class, 'store'])->name('discounts.store');
    Route::get('/discounts/{id}/edit', [DiscountController::class, 'edit'])->name('discounts.edit');
    Route::put('/discounts/{id}', [DiscountController::class, 'update'])->name('discounts.update');
    Route::delete('/discounts/{id}', [DiscountController::class, 'destroy'])->name('discounts.destroy');
});
Route::middleware(['auth', 'last.seen', 'permission:tax_groups'])->group(function () {
    Route::get('/tax-groups', [TaxGroupController::class, 'index'])->name('tax-groups.index');
    Route::get('/tax-groups/create', [TaxGroupController::class, 'create'])->name('tax-groups.create');
    Route::post('/tax-groups', [TaxGroupController::class, 'store'])->name('tax-groups.store');
    Route::get('/tax-groups/{id}/edit', [TaxGroupController::class, 'edit'])->name('tax-groups.edit');
    Route::put('/tax-groups/{id}', [TaxGroupController::class, 'update'])->name('tax-groups.update');
    Route::delete('/tax-groups/{id}', [TaxGroupController::class, 'destroy'])->name('tax-groups.destroy');
});

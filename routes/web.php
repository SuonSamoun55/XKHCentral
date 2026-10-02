<?php

use App\Http\Controllers\Api\BusinessCentral\OrderStatusController;
use App\Http\Controllers\Api\Communication\ChatController;
use App\Http\Controllers\Api\ManagementSystem\AdminNotificationController;
use App\Http\Controllers\Api\ManagementSystem\AuthController;
use App\Http\Controllers\Api\ManagementSystem\CompanyController;
use App\Http\Controllers\Api\ManagementSystem\DashboardController;
use App\Http\Controllers\Api\ManagementSystem\LoginSettingsController;
use App\Http\Controllers\Api\ManagementSystem\PermissionController;
use App\Http\Controllers\Api\ManagementSystem\RoleController;
use App\Http\Controllers\Api\ManagementSystem\StaffController;
use App\Http\Controllers\Api\ManagementSystem\WebUserController;
use App\Http\Controllers\Api\POS\Admin\ApprovalEntries\ApprovalEntriesController;
use App\Http\Controllers\Api\POS\Admin\Discounts\DiscountController;
use App\Http\Controllers\Api\POS\Admin\Items\ItemPosController;
use App\Http\Controllers\Api\POS\Admin\Items\ItemVariantPosController;
use App\Http\Controllers\Api\POS\Admin\NumberSeries\NumberSeriesController;
use App\Http\Controllers\Api\POS\Admin\Orders\AdminOrderController;
use App\Http\Controllers\Api\POS\Admin\Profile\AdminProfileController;
use App\Http\Controllers\Api\POS\Admin\StoreManagement\StoreManagementController;
use App\Http\Controllers\Api\POS\Admin\Tax\VatPostingSetupController;
use App\Http\Controllers\Api\POS\Reports\OrderReportController;
use App\Http\Controllers\Api\POS\Reports\ReportSettingsController;
use App\Http\Controllers\Api\POS\User\Cart\CartController;
use App\Http\Controllers\Api\POS\User\Daskboard\DashboardUserController;
use App\Http\Controllers\Api\POS\User\Favorites\FavoriteController;
use App\Http\Controllers\Api\POS\User\Notifications\NotificationController;
use App\Http\Controllers\Api\POS\User\Orders\HistoryController;
use App\Http\Controllers\Api\POS\User\Orders\OrderController;
use App\Http\Controllers\Api\POS\User\Products\ItemListController;
use App\Http\Controllers\Api\POS\User\Profile\UserProfileController;
use App\Models\ManagementSystem\User;
use Illuminate\Support\Facades\Route;

// ================= LOGIN =================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// Keeps "last seen" fresh while a page is open.
Route::middleware('auth')->post('/heartbeat', function () {
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

// ================= LOGGED-IN PAGES =================
// Every group below is locked to one page permission (see CheckPagePermission).
Route::middleware(['auth', 'last.seen'])->group(function () {

    // ---------- Dashboards ----------
    Route::middleware('permission:dashboard')->group(function () {
        Route::get('/admin', [DashboardController::class, 'index'])->name('pos.index');
        Route::get('/admin/dashboard/report-chart', [DashboardController::class, 'reportChart'])->name('admin.dashboard.report-chart');
        Route::get('/admin/dashboard/top-products', [DashboardController::class, 'topProductsData'])->name('admin.dashboard.top-products');
        Route::get('/admin/dashboard/overview-stats', [DashboardController::class, 'overviewStats'])->name('admin.dashboard.overview-stats');
    });

    Route::middleware('permission:home')->group(function () {
        Route::get('/', [DashboardUserController::class, 'index'])->name('user.index');
    });

    // ---------- Product Management (route names keep the old store.management.* prefix) ----------
    Route::middleware('permission:store_management')->prefix('product-management')->group(function () {
        Route::get('/', [StoreManagementController::class, 'index'])->name('store.management.index');
        Route::get('/tracking', [StoreManagementController::class, 'tracking'])->name('store.management.tracking');
        Route::get('/products/{id}/detail', [StoreManagementController::class, 'productDetail'])->name('store.management.products.detail');
        Route::post('/products/{id}/toggle', [StoreManagementController::class, 'toggleProduct'])->name('store.management.products.toggle');
        Route::post('/categories/{code}/toggle', [StoreManagementController::class, 'toggleCategory'])->name('store.management.categories.toggle');
        Route::post('/products/bulk-update', [StoreManagementController::class, 'bulkUpdateProducts'])->name('store.management.products.bulkUpdate');
        Route::post('/categories/bulk-update', [StoreManagementController::class, 'bulkUpdateCategories'])->name('store.management.categories.bulkUpdate');
        Route::post('/selling-location', [StoreManagementController::class, 'updateSellingLocation'])->name('store.management.sellingLocation.update');
        Route::post('/products/{id}/toggle-oversell', [StoreManagementController::class, 'toggleOversell'])->name('store.management.products.toggleOversell');
        Route::post('/products/oversell/bulk-out-of-stock', [StoreManagementController::class, 'bulkUpdateOversellOutOfStock'])->name('store.management.products.oversell.bulkOutOfStock');
        Route::get('/products/{id}/images', [StoreManagementController::class, 'editImages'])->name('store.management.product.images');
        Route::post('/products/{id}/image', [StoreManagementController::class, 'uploadMainImage'])->name('store.management.product.image.upload');
        Route::post('/products/{id}/mark-updated', [StoreManagementController::class, 'markUpdated'])->name('store.management.product.markUpdated');
        Route::put('/products/{id}/description', [StoreManagementController::class, 'updateDescription'])->name('store.management.product.description.update');

        Route::get('/variants', [ItemVariantPosController::class, 'manage'])->name('store.management.variants');
        Route::get('/items/{itemId}/variants', [ItemVariantPosController::class, 'index'])->name('store.management.item.variants');
        Route::post('/variants/{variantId}/image', [ItemVariantPosController::class, 'uploadImage'])->name('store.management.variants.image');
        Route::put('/variants/{variantId}/price', [ItemVariantPosController::class, 'updatePrice'])->name('store.management.variants.price');
    });

    // ---------- Web Shop (route names keep the old pos.* prefix) ----------
    Route::middleware('permission:pos')->prefix('web-shop')->group(function () {
        Route::get('/', [ItemPosController::class, 'index'])->name('pos.interface');
        Route::get('/item-detail/{id}', [ItemPosController::class, 'showItem'])->name('pos.item');
        Route::post('/sync', [ItemPosController::class, 'syncFromAl'])->name('pos.items.sync');
        Route::get('/items/{id}', [ItemPosController::class, 'detail'])->name('pos.items.detail');
        Route::get('/items/{id}/json', [ItemPosController::class, 'showItem'])->name('pos.items.json');
    });

    // ---------- Orders (admin) ----------
    Route::middleware('permission:orders')->group(function () {
        Route::get('/admin/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
        Route::post('/admin/orders/{id}/confirm', [AdminOrderController::class, 'confirm'])->name('admin.orders.confirm');
        Route::post('/admin/orders/{id}/cancel', [AdminOrderController::class, 'cancel'])->name('admin.orders.cancel');
        Route::get('/admin/order-actions', [AdminOrderController::class, 'actionHistory'])->name('admin.orders.actions');
        Route::get('/admin/orders/{id}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
    });

    // ---------- Customer shop ----------
    Route::middleware('permission:storefront')->group(function () {
        Route::get('/pos-system', [ItemListController::class, 'getItems'])->name('user.posinterface');
        Route::get('/pos-system/product/{id}', [ItemListController::class, 'showProduct'])->name('user.pos.product.detail');
    });

    Route::middleware('permission:favorites')->group(function () {
        Route::get('/pos-system/favorites', [FavoriteController::class, 'getFavorites'])->name('user.pos.favorites');
        Route::post('/pos-system/favorite-toggle', [FavoriteController::class, 'toggle'])->name('user.pos.favorite.toggle');
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

    Route::middleware('permission:order_history')->group(function () {
        Route::get('/pos-system/order-history', [HistoryController::class, 'history'])->name('user.pos.order.history');
        Route::get('/pos-system/order/{order}/bc-status', [OrderStatusController::class, 'show'])->name('user.pos.order.bc-status');
        Route::get('/pos-system/order/{id}', [HistoryController::class, 'show'])->name('user.pos.order.show');
        Route::post('/pos-system/order/{id}/cancel', [HistoryController::class, 'cancel'])->name('user.pos.order.cancel');
        Route::delete('/orders/delete-multiple', [HistoryController::class, 'deleteMultiple'])->name('user.pos.order.deleteMultiple');
    });

    Route::middleware('permission:user_notifications')->group(function () {
        Route::get('/pos-system/notifications', [NotificationController::class, 'getNotifications'])->name('user.notifications');
        Route::get('/pos-system/notifications/unread', [NotificationController::class, 'unreadNotifications'])->name('user.notifications.unread');
        Route::get('/pos-system/notifications/{id}', [NotificationController::class, 'show'])->name('user.notifications.show');
        Route::get('/pos-system/notifications/{id}/items', [NotificationController::class, 'getNotificationItems'])->name('user.notifications.items');
        Route::post('/pos-system/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('user.notifications.read');
        Route::delete('/pos-system/notifications/delete-selected', [NotificationController::class, 'deleteSelected'])->name('user.notifications.deleteSelected');
    });

    Route::middleware('permission:chat')->group(function () {
        Route::get('/pos-system/chat', [ChatController::class, 'userIndex'])->name('user.chat.index');
        Route::post('/pos-system/chat/send', [ChatController::class, 'userSend'])->name('user.chat.send');
        Route::get('/pos-system/chat/messages', [ChatController::class, 'userMessages'])->name('user.chat.messages');
    });

    Route::middleware('permission:profile')->group(function () {
        Route::get('/profile', [UserProfileController::class, 'index'])->name('profile');
        Route::get('/profile/edit', [UserProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/update', [UserProfileController::class, 'update'])->name('profile.update');
        Route::get('/profile/change-password', [UserProfileController::class, 'showChangePasswordForm'])->name('user.password.change');
        Route::put('/profile/change-password', [UserProfileController::class, 'updatePassword'])->name('user.password.update');
    });

    // ---------- Admin notifications ----------
    Route::middleware('permission:notifications')->group(function () {
        Route::get('/admin/notification', [AdminNotificationController::class, 'index'])->name('admin.notification');

        Route::prefix('admin/notifications')->name('admin.notifications.')->group(function () {
            Route::get('/', [AdminNotificationController::class, 'index'])->name('index');
            Route::get('/ajax/search-customers', [AdminNotificationController::class, 'searchCustomers'])->name('ajax.search.customers');
            Route::get('/ajax/latest', [AdminNotificationController::class, 'latestNotifications'])->name('ajax.latest');
            Route::get('/{id}', [AdminNotificationController::class, 'show'])->name('show');
            Route::post('/store', [AdminNotificationController::class, 'store'])->name('store');
            Route::post('/read/{id}', [AdminNotificationController::class, 'markAsRead'])->name('read');
            Route::post('/read-all', [AdminNotificationController::class, 'markAllAsRead'])->name('read.all');
            Route::post('/read-selected', [AdminNotificationController::class, 'markSelectedAsRead'])->name('read.selected');
            Route::delete('/delete-selected', [AdminNotificationController::class, 'deleteSelected'])->name('delete.selected');
            Route::delete('/destroy/{id}', [AdminNotificationController::class, 'destroy'])->name('destroy');
        });
    });

    // Chat has its own permission, so a role (e.g. "Support") can chat
    // without managing notifications, or the other way round.
    Route::middleware('permission:admin_chat')->group(function () {
        Route::get('/admin/notification/chat', [ChatController::class, 'adminIndex'])->name('admin.chat.index');
        Route::post('/admin/notification/chat/send', [ChatController::class, 'adminSend'])->name('admin.chat.send');
        Route::get('/admin/notification/chat/messages', [ChatController::class, 'adminMessages'])->name('admin.chat.messages');
    });

    // ---------- Admin profile (every logged-in admin/staff) ----------
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
        Route::post('/companies/{id}/clone', [CompanyController::class, 'cloneAsTest'])->name('companies.clone');
        Route::post('/companies/{id}/select', [CompanyController::class, 'select'])->name('companies.select');
        Route::post('/companies/clear-selection', [CompanyController::class, 'clearSelection'])->name('companies.clearSelection');
    });

    // ---------- Login page media (app-wide, so cross-company users only) ----------
    Route::middleware(['permission:login_settings', 'no-company-scope'])->group(function () {
        Route::get('/login-settings', [LoginSettingsController::class, 'index'])->name('login-settings.index');
        Route::put('/login-settings', [LoginSettingsController::class, 'update'])->name('login-settings.update');
    });

    // ---------- Roles & pages ----------
    Route::middleware('permission:roles')->group(function () {
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
        Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
        Route::get('/roles/{id}/edit', [RoleController::class, 'edit'])->name('roles.edit');
        Route::put('/roles/{id}', [RoleController::class, 'update'])->name('roles.update');
        Route::delete('/roles/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');
    });

    // Read-only: the pages come from RoleAndPermissionSeeder and are checked
    // by the permission:* middleware, so they aren't edited here.
    Route::middleware('permission:page_management')->group(function () {
        Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
        Route::post('/permissions/sync', [PermissionController::class, 'sync'])->name('permissions.sync');
    });

    // ---------- Customers ----------
    Route::prefix('users')->name('users.')->group(function () {
        // Used by customers too (their own picture and sync), so these two
        // check access inside the controller instead of by permission.
        Route::get('/bc-image/{bcId}', [WebUserController::class, 'getBCImage'])->name('bc-image');
        Route::post('/{id}/sync-bc', [WebUserController::class, 'syncSingleCustomer'])->name('syncOne');

        Route::middleware('permission:users')->group(function () {
            Route::get('/', [WebUserController::class, 'index'])->name('index');
            Route::get('/sync', [WebUserController::class, 'syncBCCustomers'])->name('sync');
            Route::get('/data', [WebUserController::class, 'getUsers'])->name('data');
            Route::get('/create/{id}', [WebUserController::class, 'create'])->name('create');
            Route::post('/store/{id}', [WebUserController::class, 'store'])->name('store');
            Route::get('/show/{id}', [WebUserController::class, 'show'])->name('show');
            Route::get('/edit/{id}', [WebUserController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [WebUserController::class, 'update'])->name('update');
            Route::put('/{id}/contact-details', [WebUserController::class, 'updateContactDetails'])->name('contactDetails.update');
            Route::delete('/destroy/{id}', [WebUserController::class, 'destroy'])->name('destroy');
            Route::post('/delete-selected', [WebUserController::class, 'deleteSelected'])->name('deleteSelected');
        });
    });

    // ---------- Staff ----------
    Route::middleware('permission:users')->prefix('staff')->name('staff.')->group(function () {
        Route::get('/', [StaffController::class, 'index'])->name('index');
        Route::post('/', [StaffController::class, 'store'])->name('store');
        Route::put('/{id}', [StaffController::class, 'update'])->name('update');
        Route::put('/{id}/password', [StaffController::class, 'updatePassword'])->name('updatePassword');
        Route::delete('/{id}', [StaffController::class, 'destroy'])->name('destroy');
    });

    // ---------- Setup pages ----------
    Route::middleware('permission:discounts')->group(function () {
        Route::get('/discounts', [DiscountController::class, 'index'])->name('discounts.index');
        Route::get('/discounts/create', [DiscountController::class, 'create'])->name('discounts.create');
        Route::post('/discounts', [DiscountController::class, 'store'])->name('discounts.store');
        Route::get('/discounts/{id}/edit', [DiscountController::class, 'edit'])->name('discounts.edit');
        Route::put('/discounts/{id}', [DiscountController::class, 'update'])->name('discounts.update');
        Route::delete('/discounts/{id}', [DiscountController::class, 'destroy'])->name('discounts.destroy');
    });

    Route::middleware('permission:number_series')->group(function () {
        Route::get('/number-series', [NumberSeriesController::class, 'index'])->name('number-series.index');
        Route::get('/number-series/create', [NumberSeriesController::class, 'create'])->name('number-series.create');
        Route::post('/number-series', [NumberSeriesController::class, 'store'])->name('number-series.store');
        Route::get('/number-series/{id}/edit', [NumberSeriesController::class, 'edit'])->name('number-series.edit');
        Route::put('/number-series/{id}', [NumberSeriesController::class, 'update'])->name('number-series.update');
        Route::delete('/number-series/{id}', [NumberSeriesController::class, 'destroy'])->name('number-series.destroy');
    });

    Route::middleware('permission:vat_posting_setup')->group(function () {
        Route::get('/vat-posting-setup', [VatPostingSetupController::class, 'index'])->name('vat-posting-setup.index');
        Route::post('/vat-posting-setup/sync', [VatPostingSetupController::class, 'syncFromBc'])->name('vat-posting-setup.sync');
    });

    Route::middleware('permission:approval_entries')->group(function () {
        Route::get('/approval-entries', [ApprovalEntriesController::class, 'index'])->name('approval-entries.index');
    });

    // Document Display (route names keep the old report-settings.* prefix)
    Route::middleware('permission:report_settings')->group(function () {
        Route::get('/document-display', [ReportSettingsController::class, 'index'])->name('report-settings.index');
        Route::put('/document-display', [ReportSettingsController::class, 'update'])->name('report-settings.update');
    });

    // Order PDF, used by both admins and customers, so access (order owner,
    // or staff with the "orders" permission) is checked in the controller.
    Route::get('/orders/{id}/report', [OrderReportController::class, 'preview'])->name('orders.report.preview');
    Route::get('/orders/{id}/report/raw', [OrderReportController::class, 'raw'])->name('orders.report.raw');
    Route::get('/orders/{id}/report/stream', [OrderReportController::class, 'stream'])->name('orders.report.stream');
    Route::get('/orders/{id}/report/download', [OrderReportController::class, 'download'])->name('orders.report.download');
});

// ================= OLD ADDRESSES =================
// Pages that were renamed: permanent redirects keep bookmarks working.
Route::permanentRedirect('/pos/interface', '/web-shop');
Route::permanentRedirect('/pos/items/{id}', '/web-shop/items/{id}');
Route::permanentRedirect('/pos/item-detail/{id}', '/web-shop/item-detail/{id}');
Route::permanentRedirect('/store-management', '/product-management');
Route::permanentRedirect('/store-management/tracking', '/product-management/tracking');
Route::permanentRedirect('/store/management/products/{id}/images', '/product-management/products/{id}/images');
Route::permanentRedirect('/store/management/variants', '/product-management/variants');
Route::permanentRedirect('/report-settings', '/document-display');

<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleAndPermissionSeeder extends Seeder
{

    public static array $pages = [
        // Admin side
        'dashboard' => ['label' => 'Dashboard', 'group' => 'admin', 'urls' => '/admin, /admin/dashboard/*'],
        'users' => ['label' => 'Users', 'group' => 'admin', 'urls' => '/users/*'],
        'pos' => ['label' => 'POS System', 'group' => 'admin', 'urls' => '/pos/interface, /pos/item-detail/*, /item-image/*, /pos/items/*'],
        'companies' => ['label' => 'Companies', 'group' => 'admin', 'urls' => '/companies/*'],
        'discounts' => ['label' => 'Discounts', 'group' => 'admin', 'urls' => '/discounts/*'],
        'tax_groups' => ['label' => 'Tax Groups', 'group' => 'admin', 'urls' => '/tax-groups/*'],
        'store_management' => ['label' => 'Store Management', 'group' => 'admin', 'urls' => '/store-management, /store/management/*, /items/{itemId}/variants, /items/variants/*'],
        'notifications' => ['label' => 'Notifications', 'group' => 'admin', 'urls' => '/admin/notification, /admin/notifications/*'],
        'admin_chat' => ['label' => 'Chat View', 'group' => 'admin', 'urls' => '/admin/notification/chat*'],
        'chat_support' => ['label' => 'Chat Support', 'group' => 'admin', 'urls' => '(not a URL — controls whether this role\'s accounts appear to customers as a chat contact)'],
        'orders' => ['label' => 'Orders', 'group' => 'admin', 'urls' => '/admin/orders*'],
        'roles' => ['label' => 'Roles', 'group' => 'admin', 'urls' => '/roles/*'],
        'page_management' => ['label' => 'Page List', 'group' => 'admin', 'urls' => '/permissions/*'],

        // User (customer/POS storefront) side.
        'home' => ['label' => 'Home', 'group' => 'customer', 'urls' => '/'],
        'storefront' => ['label' => 'Storefront', 'group' => 'customer', 'urls' => '/pos-system, /pos-system/product/*, /pos/products/filter'],
        'cart' => ['label' => 'Cart', 'group' => 'customer', 'urls' => '/pos-system/cart*'],
        'checkout' => ['label' => 'Checkout', 'group' => 'customer', 'urls' => '/pos-system/checkout*, /pos-system/order-success'],
        'favorites' => ['label' => 'Favorites', 'group' => 'customer', 'urls' => '/pos-system/favorites, /pos-system/favorite-toggle'],
        'order_history' => ['label' => 'Order History', 'group' => 'customer', 'urls' => '/pos-system/order*, /orders/delete-multiple'],
        'chat' => ['label' => 'Chat', 'group' => 'customer', 'urls' => '/pos-system/chat*'],
        'user_notifications' => ['label' => 'Notifications', 'group' => 'customer', 'urls' => '/pos-system/notifications*'],
        'profile' => ['label' => 'Profile', 'group' => 'customer', 'urls' => '/profile*'],
    ];

    public function run(): void
    {
        $adminPermissionIds = [];

        foreach (self::$pages as $name => $meta) {
            $permission = Permission::updateOrCreate(
                ['name' => $name],
                ['display_name' => $meta['label'], 'group' => $meta['group'], 'urls' => $meta['urls'] ?? null]
            );

            if ($meta['group'] === 'admin') {
                $adminPermissionIds[] = $permission->id;
            }
        }

        $adminRole = Role::updateOrCreate(
            ['name' => 'admin'],
            ['display_name' => 'Administrator']
        );

        $adminRole->permissions()->sync($adminPermissionIds);

      
        Role::updateOrCreate(
            ['name' => 'customer'],
            ['display_name' => 'Customer']
        );
    }
}

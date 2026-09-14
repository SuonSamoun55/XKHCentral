<?php

namespace App\View\Composers;

use App\Models\ManagementSystem\Company;
use App\Models\ManagementSystem\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class POSUserSidebarComposer
{
    public function compose(View $view): void
    {
        /** @var User $authUser */
        $authUser = Auth::user();

        $company = session('selected_company_id')
            ? Company::find(session('selected_company_id'))
            : null;

        if (!$company) {
            $company = Company::first();
        }

        $view->with([
            'authUser' => $authUser,
            'companyLogoUrl' => $this->resolveCompanyLogoUrl($company),
            'userAvatar' => $authUser->profile_image_display ?? asset('images/default-user.png'),
            // Mirrors the 'permission:dashboard' gate on the /admin route itself, so this
            // link only appears for roles that can actually get in.
            'isAdmin' => $authUser->isAdmin() || $authUser->hasPermission('dashboard'),
            'navItems' => $this->navItems(),
        ]);
    }

    private function resolveCompanyLogoUrl(?Company $company): string
    {
        if ($company && !empty($company->logo)) {
            return preg_match('/^https?:\/\//i', $company->logo)
                ? $company->logo
                : Storage::url($company->logo);
        }

        return asset('images/default-company.png');
    }

    private function navItems(): array
    {
        return [
            [
                'name' => 'Dashboard',
                'url' => '/',
                'match' => ['/', 'pos-system'],
                'icon' => 'images/aside/SidbarDaskboards.png',
                'icon_active' => 'images/aside/UserDaskboardActive.png',
            ],
            [
                'name' => 'Cart',
                'url' => '/pos-system/cart',
                'match' => ['pos-system/cart'],
                'icon' => 'images/aside/SidebarCarts.png',
                'icon_active' => 'images/aside/UserCartActive.png',
                'badge' => 'cart',
            ],
            [
                'name' => 'Favorite',
                'url' => '/pos-system/favorites',
                'match' => ['pos-system/favorites'],
                'icon' => 'images/aside/SidebarFavorites.png',
                'icon_active' => 'images/aside/FavoriteActive.png',
            ],
            [
                'name' => 'Order History',
                'url' => '/pos-system/order-history',
                'match' => ['pos-system/order-history'],
                'icon' => '/images/AdminPOS/Admin_POS_Approval_Order.png',
                'icon_active' => '/images/AdminPOS/Admin_POS_Approval_Order_active.png',
            ],
            [
                'name' => 'Notification',
                'url' => '/pos-system/notifications',
                'match' => ['pos-system/notifications'],
                'icon' => 'images/aside/SidebarNotifications.png',
                'icon_active' => 'images/aside/NotificationActive.png',
                'badge' => 'notification',
            ],
        ];
    }
}

<?php

namespace App\View\Composers;

use App\Models\ManagementSystem\Company;
use App\Models\ManagementSystem\Notification;
use App\Models\ManagementSystem\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ManagementSidebarComposer
{
    public function compose(View $view): void
    {
        /** @var User|null $authUser */
        $authUser = Auth::user();

        $company = session('selected_company_id')
            ? Company::find(session('selected_company_id'))
            : null;

        if (!$company) {
            $company = Company::first();
        }

        $canAccessPage = function (string $page) use ($authUser) {
            if (!$authUser) {
                return false;
            }

            if (strtolower((string) $authUser->role) === 'admin') {
                return true;
            }

            return $authUser->hasPermission($page);
        };

        $navItems = $this->filterNavItemsByPermission($this->navItems(), $canAccessPage);
        $onCompanySelectScreen = request()->is('companies/select');

        $activeNavItem = null;
        if (!$onCompanySelectScreen) {
            foreach ($navItems as $item) {
                foreach ($item['match'] as $pattern) {
                    if (request()->is($pattern)) {
                        $activeNavItem = $item;
                        break 2;
                    }
                }
            }
        }

        $factory = $view->getFactory();

        $view->with([
            'authUser' => $authUser,
            'unreadNotificationCount' => Notification::adminUnreadTotal(session('selected_company_id')),
            'companyLogoUrl' => $this->resolveCompanyLogoUrl($company),
            'userAvatar' => $this->resolveUserAvatar($authUser),
            'canAccessPage' => $canAccessPage,
            'navItems' => $navItems,
            'mobileMenuItems' => $this->flattenForMobile($navItems),
            'bottomNavItems' => array_values(array_filter(
                $navItems,
                fn($item) => $item['name'] !== 'Approval Entries'
            )),
            'onCompanySelectScreen' => $onCompanySelectScreen,
            'activeNavItem' => $activeNavItem,
            'activeNavIcon' => $activeNavItem['icon_active'] ?? ($activeNavItem['icon'] ?? null),
            'backUrl' => trim((string) $factory->yieldContent('backUrl', '')),
            'hideMobileChrome' => trim((string) $factory->yieldContent('hideMobileNav', '')) !== '',
            'companyName' => $company ? $company->name : null,
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

    private function resolveUserAvatar(?User $authUser): string
    {
        $default = asset('images/default-user.png');

        if (!$authUser) {
            return $default;
        }

        $possibleUserImages = [
            $authUser->profile_image_display ?? null,
            $authUser->avatar ?? null,
            $authUser->profile_image ?? null,
            $authUser->image ?? null,
            $authUser->photo ?? null,
            $authUser->bc_image_url ?? null,
            $authUser->profile_image_url ?? null,
        ];

        foreach ($possibleUserImages as $img) {
            if (empty($img)) {
                continue;
            }

            if (preg_match('/^https?:\/\//i', $img)) {
                return $img;
            }

            if (str_starts_with($img, 'storage/')) {
                return asset($img);
            }

            if (
                str_starts_with($img, 'profile_') ||
                str_starts_with($img, 'profile-images/') ||
                str_starts_with($img, 'profile_images/') ||
                str_starts_with($img, 'avatars/') ||
                str_starts_with($img, 'users/') ||
                str_starts_with($img, 'uploads/') ||
                str_starts_with($img, 'user_images/')
            ) {
                return Storage::url($img);
            }

            return asset($img);
        }

        return $default;
    }
    private function filterNavItemsByPermission(array $navItems, \Closure $canAccessPage): array
    {
        return array_values(array_filter(array_map(function ($item) use ($canAccessPage) {
            if (!empty($item['children'])) {
                $item['children'] = array_values(array_filter(
                    $item['children'],
                    fn($child) => $canAccessPage($child['permission'])
                ));

                return empty($item['children']) ? null : $item;
            }

            return $canAccessPage($item['permission']) ? $item : null;
        }, $navItems)));
    }
    private function flattenForMobile(array $items): array
    {
        $flat = [];
        foreach ($items as $item) {
            if (!empty($item['children'])) {
                foreach ($item['children'] as $child) {
                    $flat[] = $child;
                }
            } else {
                $flat[] = $item;
            }
        }
        return $flat;
    }

    private function navItems(): array
    {
        return [
            [
                'name' => 'Dashboard',
                'url' => '/admin',
                'match' => ['admin'],
                'icon' => '/images/aside/SidbarDaskboards.png',
                'icon_active' => '/images/aside/UserDaskboardActive.png',
                'permission' => 'dashboard',
            ],
            [
                'name' => 'Pos System',
                'url' => '/pos/interface',
                'match' => ['pos/interface', 'pos/*'],
                'icon' => '/images/management/managemetn_POS.png',
                'icon_active' => '/images/management/management_POS_active.png',
                'permission' => 'pos',
            ],
            [
                'name' => 'User',
                'match' => ['users', 'users/*', 'staff', 'staff/*', 'roles', 'roles/*'],
                'icon' => '/images/management/management_user.png',
                'icon_active' => '/images/management/management_user_active.png',
                'children' => [
                    [
                        'name' => 'Customer',
                        'url' => '/users',
                        'match' => ['users', 'users/*'],
                        'icon' => '/images/management/management_user.png',
                        'icon_active' => '/images/management/management_user_active.png',
                        'permission' => 'users',
                    ],
                    [
                        'name' => 'Staff',
                        'url' => '/staff',
                        'match' => ['staff', 'staff/*'],
                        'icon' => '/images/management/Stafficon.png',
                        'icon_active' => '/images/management/StaffActiveicon.png',
                        'permission' => 'users',
                    ],
                    [
                        'name' => 'Roles',
                        'url' => '/roles',
                        'match' => ['roles', 'roles/*'],
                        'icon' => '/images/management/role.png',
                        'icon_active' => '/images/management/role_active.png',
                        'permission' => 'roles',
                    ],
                ],
            ],
            [
                'name' => 'Company',
                'match' => ['companies', 'companies/*', 'permissions', 'permissions/*', 'number-series', 'number-series/*'],
                'icon' => 'images/management/management_company.png',
                'icon_active' => 'images/management/management_company_active.png',
                'children' => [
                    [
                        'name' => 'Companies',
                        'url' => '/companies',
                        'match' => ['companies', 'companies/*'],
                        'icon' => 'images/management/management_company.png',
                        'icon_active' => 'images/management/management_company_active.png',
                        'permission' => 'companies',
                    ],
                    [
                        'name' => 'Permission Page',
                        'url' => '/permissions',
                        'match' => ['permissions', 'permissions/*'],
                        'icon' => '/images/management/pagelist.png',
                        'icon_active' => '/images/management/pagelist_active.png',
                        'permission' => 'page_management',
                    ],
                    [
                        'name' => 'Number Series',
                        'url' => '/number-series',
                        'match' => ['number-series', 'number-series/*'],
                        // Only the active-state PNG exists on disk
                        // (public/images/management/NumberSeries.png is
                        // missing) — falls back to the bootstrap icon when
                        // this item isn't the active page.
                        'icon' => '/images/management/AumberSeries.png',
                        'icon_active' => '/images/management/NumberSeriesActive.png',
                        'permission' => 'number_series',
                    ],
                ],
            ],
        ];
    }
}

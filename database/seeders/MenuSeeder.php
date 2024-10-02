<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = $this->getMenus();

        foreach ($menus as $menu) {
            // Use updateOrCreate to handle both creation and updating of records
            $createdMenu = Menu::updateOrCreate(
                ['slug' => $menu['slug']], // Unique identifier
                $this->prepareMenuData($menu) // Data to update or insert
            );

            // Check if submenu exists and create submenus
            if (isset($menu['submenu']) && count($menu['submenu']) > 0) {
                $this->createSubMenus($createdMenu->id, $menu['submenu']);
            }
        }
    }

    /**
     * Get the array of menu data.
     */
    private function getMenus(): array
    {
        return [
            [
                'slug' => 'dashboard',
                'label' => 'Dashboard',
                'route' => 'dashboard',
                'icon' => 'mage:dashboard-fill',
                'roles' => [], // Accessible by everyone
                'submenu' => [],
            ],
            [
                'slug' => 'user-management',
                'label' => 'Pengguna',
                'route' => null, // Parent item, no direct route
                'icon' => 'ic:round-manage-accounts',
                'roles' => ['admin', 'staff'], // Restricted to admin and staff
                'submenu' => [
                    [
                        'slug' => 'users-overview',
                        'label' => 'Overview',
                        'route' => 'users-overview',
                        'icon' => 'material-symbols:overview',
                        'roles' => ['admin', 'staff'],
                    ],
                    [
                        'slug' => 'user-manager',
                        'label' => 'Semua',
                        'route' => 'user-manager',
                        'icon' => 'mdi:account-group',
                        'roles' => ['admin'],
                    ],
                    [
                        'slug' => 'student-manager',
                        'label' => 'Siswa',
                        'route' => 'student-manager',
                        'icon' => 'mdi:account-school',
                        'roles' => ['admin', 'staff'],
                    ],
                    [
                        'slug' => 'teacher-manager',
                        'label' => 'Guru',
                        'route' => 'teacher-manager',
                        'icon' => 'dashicons:businessman',
                        'roles' => ['admin', 'staff'],
                    ],
                    [
                        'slug' => 'supervisor-manager',
                        'label' => 'Supervisor',
                        'route' => 'supervisor-manager',
                        'icon' => 'mdi:account-check',
                        'roles' => ['admin', 'staff'],
                    ],
                    [
                        'slug' => 'admin-manager',
                        'label' => 'Admin',
                        'route' => 'admin-manager',
                        'icon' => 'eos-icons:admin',
                        'roles' => ['admin', 'staff'],
                    ],
                ],
            ],
            [
                'slug' => 'setting',
                'label' => 'Pengaturan',
                'route' => 'setting',
                'icon' => 'ph:gear-fill',
                'roles' => ['admin'],
                'submenu' => [],
            ],
        ];
    }

    /**
     * Prepare menu data for insertion or updating.
     */
    private function prepareMenuData(array $menu): array
    {
        return [
            'slug' => $menu['slug'] ?? '',
            'label' => $menu['label'] ?? '',
            'route' => $menu['route'] ?? null,
            'icon' => $menu['icon'] ?? '',
            'roles' => $menu['roles'] ?? [], // Store roles as an array
        ];
    }

    /**
     * Create submenus for a given parent menu ID.
     */
    private function createSubMenus(int $parentId, array $submenus): void
    {
        foreach ($submenus as $submenu) {
            Menu::updateOrCreate(
                ['slug' => $submenu['slug']],
                array_merge($this->prepareMenuData($submenu), ['parent_id' => $parentId])
            );
        }
    }
}

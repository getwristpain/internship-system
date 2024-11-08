<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Menu::truncate();

        $menus = $this->getMenus();

        foreach ($menus as $menu) {
            // Use updateOrCreate to handle both creation and updating of records
            $createdMenu = Menu::create(
                $this->prepareMenuData($menu) // Data to update or insert
            );

            // Check if submenu exists and create submenus
            if (isset($menu['submenu']) && count($menu['submenu']) > 0) {
                $this->createSubMenus($createdMenu->id, $menu['submenu']);
            }
        }
    }

    /**
     * Get the array of menu data from JSON file.
     */
    private function getMenus(): array
    {
        $jsonPath = resource_path('data/menu.json');
        if (File::exists($jsonPath)) {
            $menus = File::get($jsonPath);
            return json_decode($menus, true);
        }

        return [];
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

<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    public array $menuItems = [
        'all' => [
            [
                'name' => 'Dashboard',
                'route' => 'dashboard',
                'icon' => 'mage:dashboard-fill',
                'label' => 'Dashboard',
                'submenu' => [],
            ],
        ],
        'admin' => [
            [
                'name' => 'User Management',
                'icon' => 'ic:round-manage-accounts',
                'label' => 'Pengguna',
                'submenu' => [
                    [
                        'name' => 'Overview',
                        'route' => 'user-overview',
                        'icon' => 'material-symbols:overview',
                        'label' => 'Overview',
                    ],
                    [
                        'name' => 'Manager',
                        'route' => 'user-manager',
                        'icon' => 'mdi:account-group',
                        'label' => 'Manager',
                    ],
                ],
            ],
        ],
    ];

    public array $filteredMenuItems = [];
    public array $openSubmenus = [];
    public bool $open = false;

    public function mount(): void
    {
        $this->loadMenu();
    }

    #[On('toggleSidebar')]
    public function toggleSidebar(bool $open): void
    {
        $this->open = $open;
    }

    private function loadMenu(): void
    {
        $user = Auth::user();
        $roles = $user->roles->pluck('name')->toArray();
        $this->filteredMenuItems = $this->menuItems['all'];

        foreach ($roles as $role) {
            if (isset($this->menuItems[$role])) {
                $this->filteredMenuItems = array_merge($this->filteredMenuItems, $this->menuItems[$role]);
            }
        }
    }

    public function navigate(string $menuName): void
    {
        $item = $this->findMenuItem($menuName);

        if ($item) {
            $this->handleNavigation($item);
        } else {
            session()->flash('error', "Menu item not found: $menuName");
        }
    }

    private function findMenuItem(string $menuName): ?array
    {
        return collect($this->filteredMenuItems)->firstWhere('name', $menuName);
    }

    private function handleNavigation(array $item): void
    {
        if (!empty($item['submenu'])) {
            $this->toggleSubmenu($item['name']);
        } elseif (isset($item['route']) && Route::has($item['route'])) {
            $this->redirect(route($item['route']), navigate: true);
        } else {
            session()->flash('error', 'Route not found for menu item: ' . $item['name']);
        }
    }

    private function toggleSubmenu(string $menuName): void
    {
        $this->openSubmenus = in_array($menuName, $this->openSubmenus) ? array_filter($this->openSubmenus, fn($name) => $name !== $menuName) : [...$this->openSubmenus, $menuName];
    }

    public function isActive(string $routeName): bool
    {
        return Route::currentRouteName() === $routeName;
    }
}; ?>

<nav class="flex flex-col gap-2">
    @foreach ($filteredMenuItems as $item)
        <div>
            <!-- Main menu items -->
            <a type="button" title="{{ $item['label'] ?? '' }}" wire:click="navigate('{{ $item['name'] }}')"
                class="group relative flex cursor-pointer gap-2 w-full rounded-lg items-center {{ $this->isActive($item['route'] ?? '') ? 'bg-black text-white font-bold' : 'hover:bg-gray-200 font-medium transition ease-in-out duration-150' }} {{ !$open ? 'justify-center p-3' : 'px-4 py-2' }}">

                <!-- Icon -->
                <iconify-icon class="scale-125" icon="{{ $item['icon'] ?? '' }}"></iconify-icon>

                <!-- Label that appears on hover -->
                <span
                    class="items-center gap-2 {{ $open ? 'flex' : 'hidden group-hover:flex absolute left-full pl-2 pr-4 py-2 whitespace-nowrap rounded-r-lg transition-transform transform -translate-x-2' }} {{ $this->isActive($item['route'] ?? '') ? 'bg-black text-white' : 'group-hover:bg-gray-200 transition ease-in-out duration-150' }}">
                    {{ $item['label'] ?? '' }}

                    <!-- Dropdown icon -->
                    @if (!empty($item['submenu']))
                        <iconify-icon
                            class="text-xl transition-transform duration-300 {{ in_array($item['name'], $openSubmenus) ? 'rotate-180' : '' }}"
                            icon="mdi:chevron-down"></iconify-icon>
                    @endif
                </span>
            </a>

            <!-- Submenu items -->
            @if (!empty($item['submenu']) && in_array($item['name'], $openSubmenus))
                @foreach ($item['submenu'] as $submenu)
                    <div class="{{ !$open ?: 'pl-8' }}" :key="$submenu['name']">
                        <a href="{{ Route::has($submenu['route']) ? route($submenu['route']) : '' }}" type="button"
                            title="{{ $submenu['label'] ?? '' }}"
                            class="group relative flex cursor-pointer gap-2 p-3 w-full rounded-lg items-center {{ $this->isActive($submenu['route'] ?? '') ? 'bg-black text-white font-bold' : 'hover:bg-gray-200 font-medium transition ease-in-out duration-150' }} {{ !$open ? 'justify-center' : '' }}"
                            wire:navigate>

                            <!-- Icon -->
                            <iconify-icon class="scale-125" icon="{{ $submenu['icon'] ?? '' }}"></iconify-icon>

                            <!-- Label that appears on hover -->
                            <span
                                class="{{ $open ? 'block' : 'hidden group-hover:block absolute left-full p-2 whitespace-nowrap rounded-r-lg transition-transform transform -translate-x-2' }} {{ $this->isActive($submenu['route'] ?? '') ? 'bg-black text-white' : 'group-hover:bg-gray-200 transition ease-in-out duration-150' }}">
                                {{ $submenu['label'] ?? '' }}
                            </span>
                        </a>
                    </div>
                @endforeach
            @endif
        </div>
    @endforeach
</nav>

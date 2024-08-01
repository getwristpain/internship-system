<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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
            [
                'name' => 'Management',
                'icon' => 'mdi:settings',
                'label' => 'Management',
                'submenu' => [
                    [
                        'name' => 'Users',
                        'route' => 'users.index',
                        'icon' => 'mdi:account-group',
                        'label' => 'Users',
                    ],
                    [
                        'name' => 'Roles',
                        'route' => 'roles.index',
                        'icon' => 'mdi:shield-account',
                        'label' => 'Roles',
                    ],
                ],
            ],
        ],
    ];

    public array $filteredMenuItems = [];
    public array $openSubmenus = [];

    public string $activeRoute = '';
    public bool $open = false;

    protected $listeners = ['toggleSidebar'];

    public function mount()
    {
        $user = Auth::user();
        $roles = $user->roles->pluck('name')->toArray();
        $this->filteredMenuItems = $this->menuItems['all'];

        foreach ($roles as $role) {
            if (isset($this->menuItems[$role])) {
                $this->filteredMenuItems = array_merge($this->filteredMenuItems, $this->menuItems[$role]);
            }
        }

        $this->activeRoute = request()->route()->getName();
    }

    public function toggleSidebar($open)
    {
        $this->open = $open;
    }

    public function navigate($menuName)
    {
        $item = collect($this->filteredMenuItems)->firstWhere('name', $menuName);

        if (isset($item['submenu']) && !empty($item['submenu'])) {
            $this->openSubmenus = in_array($menuName, $this->openSubmenus) ? array_filter($this->openSubmenus, fn($name) => $name !== $menuName) : [...$this->openSubmenus, $menuName];
        } else {
            if (!empty($item['route']) && Route::has($item['route'])) {
                $this->activeRoute = $item['route'];
                return redirect(route($item['route']));
            }
        }
    }

    public function isActive($routeName): bool
    {
        return $this->activeRoute === $routeName;
    }
}; ?>

<nav class="flex flex-col gap-2">
    @foreach ($filteredMenuItems as $item)
        <div>
            <!-- Main menu item button -->
            <button type="button" title="{{ $item['label'] ?? '' }}" wire:click="navigate('{{ $item['name'] }}')"
                class="group relative flex cursor-pointer gap-2 p-2 w-full rounded-xl items-center {{ $isActive($item['route']) ? 'bg-black text-white font-bold' : 'hover:bg-gray-200 font-medium transition ease-in-out duration-150' }} {{ !$open ? 'justify-center' : '' }}">

                <!-- Icon -->
                <iconify-icon class="text-xl" icon="{{ $item['icon'] ?? '' }}"></iconify-icon>

                <!-- Label that appears on hover -->
                <span
                    class="items-center gap-2 {{ $open ? 'flex' : 'hidden group-hover:flex absolute left-full p-2 pl-2 whitespace-nowrap rounded-r-xl transition-transform transform -translate-x-2' }} {{ $isActive($item['route']) ? 'bg-black text-white' : 'group-hover:bg-gray-200 transition ease-in-out duration-150' }}">
                    {{ $item['label'] ?? '' }}

                    <!-- Dropdown icon -->
                    @if (!empty($item['submenu']))
                        <iconify-icon
                            class="text-xl transition-transform duration-300 {{ in_array($item['name'], $openSubmenus) ? 'rotate-180' : '' }}"
                            icon="mdi:chevron-down"></iconify-icon>
                    @endif
                </span>
            </button>

            <!-- Submenu items -->
            @if (!empty($item['submenu']) && in_array($item['name'], $openSubmenus))
                @foreach ($item['submenu'] as $submenu)
                    <div class="{{ !$open ?: 'pl-8' }}">
                        <button type="button" title="{{ $submenu['label'] ?? '' }}"
                            wire:click="navigate('{{ $submenu['name'] }}')"
                            class="group relative flex cursor-pointer gap-2 p-2 w-full rounded-xl items-center {{ $isActive($submenu['route']) ? 'bg-black text-white font-bold' : 'hover:bg-gray-200 font-medium transition ease-in-out duration-150' }} {{ !$open ? 'justify-center' : '' }}">

                            <!-- Icon -->
                            <iconify-icon class="text-xl" icon="{{ $submenu['icon'] ?? '' }}"></iconify-icon>

                            <!-- Label that appears on hover -->
                            <span
                                class="{{ $open ? 'block' : 'hidden group-hover:block absolute left-full p-2 pl-2 whitespace-nowrap rounded-r-xl transition-transform transform -translate-x-2' }} {{ $isActive($submenu['route']) ? 'bg-black text-white' : 'group-hover:bg-gray-200 transition ease-in-out duration-150' }}">
                                {{ $submenu['label'] ?? '' }}
                            </span>
                        </button>
                    </div>
                @endforeach
            @endif
        </div>
    @endforeach
</nav>

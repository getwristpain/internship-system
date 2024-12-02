<?php

use App\Models\Menu;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    public array $filteredMenuItems = [];
    public array $openSubmenus = [];
    public bool $isSidebarOpen = true;
    public string $activeMenu = '';

    public function mount(): void
    {
        $this->loadMenu();
        $this->loadSessionData();
        $this->activeMenu = Route::currentRouteName();
    }

    private function loadSessionData(): void
    {
        // Ambil data session dengan nilai default $this->isSidebarOpen
        $this->isSidebarOpen = Session::get('toggle-sidebar', $this->isSidebarOpen);
    }

    #[On('toggleSidebar')]
    public function toggleSidebar(bool $open): void
    {
        $this->isSidebarOpen = $open;
    }

    private function loadMenu(): void
    {
        $userRoles = Auth::user()->roles->pluck('name')->toArray();

        $this->filteredMenuItems = Menu::with('submenus')
            ->root() // Get only root menus
            ->get()
            ->filter(fn($item) => $this->isAccessible($item, $userRoles))
            ->toArray();
    }

    private function isAccessible($item, array $userRoles): bool
    {
        // Check if the user has access to the menu item
        if (empty($item->roles) || array_intersect($userRoles, $item->roles)) {
            // Filter the submenus based on user roles
            $item->submenus = $item->submenus->filter(fn($subItem) => empty($subItem->roles) || array_intersect($userRoles, $subItem->roles));
            return true;
        }
        return false;
    }

    protected function canAccessMenu($item)
    {
        return (Route::has($item['route']) && empty($item['roles'])) || (Route::has($item['route']) && (!empty($item['roles']) && array_intersect(Auth::user()->roles->pluck('name')->toArray(), $item['roles'])));
    }

    public function navigate(string $menuSlug): void
    {
        $item = $this->findMenuItem($menuSlug);

        if ($item) {
            $this->handleNavigation($item);
        } else {
            flash()->error("Menu item not found: $menuSlug");
        }
    }

    private function findMenuItem(string $menuSlug): ?array
    {
        return collect($this->filteredMenuItems)->firstWhere('slug', $menuSlug);
    }

    private function handleNavigation(array $item): void
    {
        if (!empty($item['submenus'])) {
            $this->toggleSubmenu($item['slug']);
        } elseif (isset($item['route']) && Route::has($item['route'])) {
            $this->activeMenu = $item['route'] ?? '';
            $this->redirect(route($item['route']), navigate: true);
        } else {
            flash()->error('Route not found for menu item: ' . $item['label']);
        }
    }

    private function toggleSubmenu(string $menuSlug): void
    {
        if (in_array($menuSlug, $this->openSubmenus)) {
            $this->openSubmenus = array_filter($this->openSubmenus, fn($name) => $name !== $menuSlug);
        } else {
            $this->openSubmenus[] = $menuSlug;
        }
    }

    public function isActive(string $routeName): bool
    {
        return $this->activeMenu === $routeName;
    }
};

?>

<nav class="flex flex-col gap-2">
    @foreach ($filteredMenuItems as $item)
        <div class="flex flex-col gap-1">
            @if (!empty($item['submenus']) || isset($item['route']))
                <!-- Main menu items -->
                <a type="button" title="{{ $item['label'] ?? '' }}" wire:click="navigate('{{ $item['slug'] }}')"
                    class="group relative flex cursor-pointer gap-2 w-full rounded-lg items-center {{ $this->isActive($item['route'] ?? '') ? 'bg-black text-white font-bold' : 'hover:bg-gray-200 font-medium transition ease-in-out duration-150' }} {{ $isSidebarOpen ? 'px-4 py-2' : 'justify-center p-3' }}">

                    <!-- Icon -->
                    <iconify-icon class="scale-125" icon="{{ $item['icon'] ?? '' }}"></iconify-icon>

                    <!-- Label that appears on hover -->
                    <span
                        class="items-center gap-2 {{ $isSidebarOpen ? 'flex' : 'hidden group-hover:flex absolute left-full pl-2 pr-4 py-2 whitespace-nowrap rounded-r-lg transition-transform transform -translate-x-2' }} {{ $this->isActive($item['route'] ?? '') ? 'bg-black text-white' : 'group-hover:bg-gray-200 transition ease-in-out duration-150' }}">
                        {{ $item['label'] ?? '' }}

                        <!-- Dropdown icon -->
                        @if (!empty($item['submenus']))
                            <iconify-icon
                                class="text-xl transition-transform duration-300 {{ in_array($item['slug'], $openSubmenus) ? 'rotate-180' : '' }}"
                                icon="mdi:chevron-down"></iconify-icon>
                        @endif
                    </span>
                </a>

                <!-- Submenu items -->
                @if (!empty($item['submenus']) && in_array($item['slug'], $openSubmenus))
                    @foreach ($item['submenus'] as $submenu)
                        @if ($this->canAccessMenu($submenu))
                            <div class="{{ !$isSidebarOpen ?: 'pl-8' }}" :key="$submenu['slug']">
                                <a href="{{ Route::has($submenu['route']) ? route($submenu['route']) : '#' }}"
                                    type="button" title="{{ $submenu['label'] ?? '' }}"
                                    class="group relative flex cursor-pointer gap-2 p-3 w-full rounded-lg items-center {{ $this->isActive($submenu['route'] ?? '') ? 'bg-black text-white font-bold' : 'hover:bg-gray-200 font-medium transition ease-in-out duration-150' }} {{ !$isSidebarOpen ? 'justify-center' : '' }}"
                                    wire:navigate>

                                    <!-- Icon -->
                                    <iconify-icon class="scale-125" icon="{{ $submenu['icon'] ?? '' }}"></iconify-icon>

                                    <!-- Label that appears on hover -->
                                    <span
                                        class="{{ $isSidebarOpen ? 'block' : 'hidden group-hover:block absolute left-full p-2 whitespace-nowrap rounded-r-lg transition-transform transform -translate-x-2' }} {{ $this->isActive($submenu['route'] ?? '') ? 'bg-black text-white' : 'group-hover:bg-gray-200 transition ease-in-out duration-150' }}">
                                        {{ $submenu['label'] ?? '' }}
                                    </span>
                                </a>
                            </div>
                        @endif
                    @endforeach
                @endif
            @endif
        </div>
    @endforeach
</nav>

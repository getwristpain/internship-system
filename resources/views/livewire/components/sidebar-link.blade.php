<?php

use Illuminate\Support\Facades\Route;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    public array $item = [];
    public bool $active = false;
    public bool $open = true;

    public function mount(array $item)
    {
        $this->item = $item;
        $this->isActive($this->item);
    }

    private function isActive(array $item)
    {
        $routeName = request()->route()->getName();
        $this->active = $routeName === $item['route'];
    }

    #[On('toggleSidebar')]
    public function toggleSidebar($open)
    {
        $this->open = $open;
    }

    public function navigate()
    {
        if (!empty($this->item['route']) && Route::has($this->item['route'])) {
            return $this->redirect(route($this->item['route']));
        }
    }
};
?>

<a type="button" title="{{ $item['label'] ?? '' }}" wire:click="navigate"
    class="group relative flex cursor-pointer gap-2 w-full rounded-lg items-center {{ $active ? 'bg-black text-white font-bold' : 'hover:bg-gray-200 font-medium transition ease-in-out duration-150' }} {{ !$open ? 'justify-center p-3' : 'px-4 py-2' }}">

    <!-- Icon -->
    <iconify-icon class="scale-125" icon="{{ $item['icon'] ?? '' }}"></iconify-icon>

    <!-- Label that appears on hover -->
    <span
        class="{{ $open ? 'block' : 'hidden group-hover:block absolute left-full p-2 whitespace-nowrap rounded-r-lg transition-transform transform -translate-x-2' }} {{ $active ? 'bg-black text-white' : 'group-hover:bg-gray-200 transition ease-in-out duration-150' }}">
        {{ $item['label'] ?? '' }}
    </span>
</a>

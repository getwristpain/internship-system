<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Component;

new class extends Component {
    public array $item = [];
    public bool $active = false;
    public bool $open = false;

    protected $listeners = ['toggleSidebar'];

    public function toggleSidebar($open)
    {
        $this->open = $open;
    }

    public function mount(array $item)
    {
        $this->item = $item;

        $routeName = request()->route()->getName();
        $this->active = $routeName === $item['route'];
    }

    public function navigate()
    {
        // Ensure route is valid before redirecting
        if (!empty($this->item['route']) && Route::has($this->item['route'])) {
            return $this->redirect(route($this->item['route']), navigate: true);
        }
    }
};
?>

<a type="button" title="{{ $item['label'] ?? '' }}" wire:click="navigate"
    class="group relative flex cursor-pointer gap-2 p-2 w-full rounded-xl items-center {{ $active ? 'bg-black text-white font-bold' : 'hover:bg-gray-200 font-medium transition ease-in-out duration-150' }} {{ !$open ? 'justify-center' : '' }}">

    <!-- Icon -->
    <iconify-icon class="text-xl" icon="{{ $item['icon'] ?? '' }}"></iconify-icon>

    <!-- Label that appears on hover -->
    <span
        class="{{ $open ? 'block' : 'hidden group-hover:block absolute left-full p-2 pl-2 whitespace-nowrap rounded-r-xl transition-transform transform -translate-x-2' }} {{ $active ? 'bg-black text-white' : 'group-hover:bg-gray-200 transition ease-in-out duration-150' }}">
        {{ $item['label'] ?? '' }}
    </span>
</a>

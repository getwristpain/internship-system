<?php

use Livewire\Volt\Component;

new class extends Component {
    public bool $open = false;
    public bool $active = false;
    public array $link = [];

    protected $listeners = ['toggleSidebar'];

    public function toggleSidebar($open)
    {
        $this->open = $open;
    }

    public function mount(array $link = [])
    {
        $this->link = $link;
        $route = request()->route()->getName();
        $this->active = isset($link['route']) && $route === $link['route'];
    }
}; ?>

<a href="{{ !empty($link['route']) && Route::has($link['route']) ? route($link['route']) : '#' }}"
    title="{{ $link['label'] }}" wire:navigate
    class="relative flex cursor-pointer gap-2 p-2 w-full rounded-xl items-center {{ $active ? 'bg-black text-white font-bold' : 'hover:bg-gray-200 font-medium transition ease-in-out duration-150' }} {{ !$open ? 'justify-center' : '' }} group">

    <!-- Icon -->
    <iconify-icon class="text-xl square" icon="{{ $link['icon'] }}"></iconify-icon>

    <!-- Label that appears on hover -->
    <span
        class="{{ $open ? 'block' : 'hidden group-hover:block absolute left-full p-2 pl-2 whitespace-nowrap rounded-r-xl transition-transform transform -translate-x-2' . ' ' . ($active ? 'bg-black text-white' : 'group-hover:bg-gray-200 transition ease-in-out duration-150') }}">{{ $link['label'] }}</span>
</a>

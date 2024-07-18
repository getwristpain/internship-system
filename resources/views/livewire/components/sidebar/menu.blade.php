<?php

use Livewire\Volt\Component;

new class extends Component {
    public bool $open = true;
    public string $activeLink;
    public array $link;

    protected $listeners = ['toggleSidebar'];

    public function toggleSidebar($open)
    {
        $this->open = $open;
    }

    public function mount(string $activeLink = '', array $link = [])
    {
        $this->activeLink = $activeLink;
        $this->link = $link;
    }

    public function navigate()
    {
        $this->redirect(route($this->link['route']));
    }
}; ?>

<a wire:click="navigate"
    class="flex cursor-pointer gap-2 p-2 w-full rounded-xl items-center {{ $activeLink === $link['name'] ? 'bg-black text-white font-bold' : 'font-medium hover:bg-gray-200 transition ease-in-out duration-150' }} {{ !$open ? 'justify-center' : '' }}">
    <iconify-icon class="text-xl square" icon="{{ $link['icon'] }}"></iconify-icon>
    <span class="{{ $open ? 'block' : 'hidden' }}">{{ $link['label'] }}</span>
</a>

<?php

use Livewire\Volt\Component;

new class extends Component {
    public bool $open = false;

    public array $helpLink = ['name' => 'Help', 'route' => 'help', 'icon' => 'mage:question-mark-circle-fill', 'label' => 'Pusat Bantuan'];

    protected $listeners = ['toggleSidebar'];

    public function toggleSidebar($open)
    {
        $this->open = $open;
    }
}; ?>

<aside class="sticky top-0 left-0 h-screen bg-white border-r">
    <div class="flex flex-col justify-between gap-4 h-full px-2 py-4 {{ $open ? 'w-52' : 'w-fit' }}">
        <div class="">
            <div class="flex gap-2 items-center mb-12">
                <img class="h-8 w-auto square" src="{{ asset('img/logo.png') }}" alt="Application Logo">
                <span class="font-bold {{ $open ? 'inline' : 'hidden' }}">SMK Negeri 2 Klaten</span>
            </div>

            <livewire:components.sidebar.menu />
        </div>

        <div class="w-full flex flex-col gap-2 {{ !$open ? 'center-items' : '' }}">
            <livewire:components.sidebar.link :link="$helpLink" />
        </div>
    </div>
</aside>

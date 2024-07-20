<?php

use Livewire\Volt\Component;

new class extends Component {
    public $open = true;
    public string $activeLink = '';
    public $links = [
        ['name' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'mage:dashboard-2-fill', 'label' => 'Dashboard'],
        ['name' => 'Registration', 'route' => 'registration', 'icon' => 'mage:file-check-fill', 'label' => 'Pendaftaran']];

    protected $listeners = ['toggleSidebar'];

    public function toggleSidebar($open)
    {
        $this->open = $open;
    }

    public function mount()
    {
        // Set active link based on current route
        foreach ($this->links as $link) {
            if (request()->routeIs($link['route'])) {
                $this->activeLink = $link['name'];
                break;
            }
        }
    }
}; ?>

<aside class="sticky top-0 left-0 h-screen bg-white border-r">
    <div class="flex flex-col justify-between gap-4 h-full px-2 py-4 {{ $open ? 'w-52' : 'w-fit' }}">
        <div class="">
            <div class="flex gap-2 items-center mb-12">
                <img class="h-8 w-auto square" src="{{ asset('img/smkn2klaten.png') }}" alt="Application Logo">
                <span class="font-bold {{ $open ? 'inline' : 'hidden' }}">SMK Negeri 2 Klaten</span>
            </div>

            <nav class="w-full h-full overflow-y-scroll scrollbar-hidden flex flex-col gap-2 {{ !$open ? 'center-items' : '' }}">
                @foreach ($links as $link)
                    <livewire:components.sidebar.menu :link="$link" :activeLink="$activeLink" :key="$loop->index" />
                @endforeach
            </nav>
        </div>

        <div>
            <div class="flex gap-2 items-center">
                <img class="h-8 w-auto square" src="{{ asset('img/smkn2klaten.png') }}" alt="Application Logo">
                <span class="font-bold {{ $open ? 'inline' : 'hidden' }}">SMK Negeri 2 Klaten</span>
            </div>
        </div>
    </div>
</aside>

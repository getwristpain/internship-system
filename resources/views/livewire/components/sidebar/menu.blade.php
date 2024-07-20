<?php

use Livewire\Volt\Component;

new class extends Component {
    public bool $open = false;
    public array $links = [['name' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'mage:dashboard-2-fill', 'label' => 'Dashboard'], ['name' => 'Registration', 'route' => 'registration', 'icon' => 'mage:file-check-fill', 'label' => 'Pendaftaran']];

    protected $listeners = ['toggleSidebar'];

    public function toggleSidebar($open)
    {
        $this->open = $open;
    }
}; ?>

<nav class="w-full h-full flex flex-col gap-2 {{ !$open ? 'center-items' : '' }}">
    @foreach ($links as $link)
        <livewire:components.sidebar.link :link="$link" :key="$loop->index" />
    @endforeach
</nav>

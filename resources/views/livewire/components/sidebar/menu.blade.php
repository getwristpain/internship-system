<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
    public bool $open = false;
    public array $links = [];

    protected $listeners = ['toggleSidebar'];

    public function mount()
    {
        $user = Auth::user();
        $this->links = $this->getLinksByRole($user->getRoleSlug()->first());
    }

    protected function getLinksByRole($role)
    {
        $links = [];

        switch ($role) {
            case 'owner' || 'admin' || 'department-staff':
                $links = [
                    [
                        'name' => 'Dashboard',
                        'route' => 'dashboard',
                        'icon' => 'mage:dashboard-2-fill',
                        'label' => 'Dashboard',
                    ],
                ];
                break;

            case 'student':
                $links = [
                    [
                        'name' => 'Dashboard',
                        'route' => 'dashboard',
                        'icon' => 'mage:dashboard-2-fill',
                        'label' => 'Dashboard',
                    ],
                    [
                        'name' => 'Registration',
                        'route' => 'registration',
                        'icon' => 'mage:file-check-fill',
                        'label' => 'Pendaftaran',
                    ],
                ];
                break;
        }

        return $links;
    }

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

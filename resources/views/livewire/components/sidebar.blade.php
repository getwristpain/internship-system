<?php

use App\Models\School;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    public School $school;
    public bool $open = true;
    public string $logo = '';
    public string $brand = '';

    public array $additionalMenu = [
        [
            'name' => 'Help',
            'route' => 'help',
            'icon' => 'mage:question-mark-circle-fill',
            'label' => 'Pusat Bantuan',
        ],
    ];

    public function mount()
    {
        $this->loadSessionData();
        $this->loadSchool();
    }

    private function loadSessionData(): void
    {
        // Ambil data session dengan nilai default $this->open
        $this->open = Session::get('toggle-sidebar', $this->open);
    }

    #[On('toggleSidebar')]
    public function toggleSidebar($open)
    {
        $this->open = $open;
    }

    protected function loadSchool()
    {
        $this->school = School::first();
        $this->brand = $this->school->name ?? '';
        $this->logo = $this->school->logo ?? asset('img/logo.png');
    }
}; ?>

<div class="sticky top-0 left-0 z-10 h-screen bg-white border-r">
    <div class="flex flex-col justify-between gap-4 h-full px-2 py-4 max-w-sm {{ $open ? 'w-full' : 'w-fit' }}">
        <div class="space-y-12">
            <div class="flex items-center justify-center w-full h-8 px-2 space-x-2">
                <x-application-logo :logo="$logo" class="max-h-6" />
                <a wire:navigate href="{{ url('/') }}"
                    class="font-bold text-heading text-nowrap {{ $open ? 'block' : 'hidden' }}">{{ $brand }}</a>
            </div>

            <div>
                <livewire:components.sidebar-menu />
            </div>
        </div>

        <div class="w-full flex flex-col gap-2 {{ !$open ? 'items-center' : '' }}">
            @foreach ($additionalMenu as $item)
                <livewire:components.sidebar-link :item="$item" :key="$item['name']" />
            @endforeach
        </div>
    </div>
</div>

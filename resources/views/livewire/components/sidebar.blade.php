<?php

use App\Models\School;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    public School $school;
    public bool $open = false;

    public array $additionalMenu = [
        [
            'name' => 'Setting',
            'route' => 'setting',
            'icon' => 'ph:gear-fill',
            'label' => 'Pengaturan',
        ],
        [
            'name' => 'Help',
            'route' => 'help',
            'icon' => 'mage:question-mark-circle-fill',
            'label' => 'Pusat Bantuan',
        ],
    ];

    public function mount()
    {
        $this->school = School::first();
    }

    #[On('toggleSidebar')]
    public function toggleSidebar($open)
    {
        $this->open = $open;
    }
}; ?>

<aside class="sticky top-0 left-0 h-screen bg-white border-r">
    <div class="flex flex-col justify-between gap-4 h-full px-2 py-4 {{ $open ? 'w-52' : 'w-fit' }}">
        <div>
            <div class="flex items-center justify-center w-full h-8 gap-2 mb-12">
                <span>
                    <x-application-logo logo="{{ asset('img/logo.png') }}" class="h-8" />
                </span>
                <span class="font-bold {{ $open ? 'block' : 'hidden' }}">
                    {{ $school->name ?? config('app.name') }}
                </span>
            </div>

            <livewire:components.sidebar-menu />
        </div>

        <div class="w-full flex flex-col gap-2 {{ !$open ? 'center-items' : '' }}">
            @foreach ($additionalMenu as $item)
                <livewire:components.sidebar-link :item="$item" :key="$item['name']" />
            @endforeach
        </div>
    </div>
</aside>

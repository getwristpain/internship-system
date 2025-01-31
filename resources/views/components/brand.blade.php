<?php

use App\Models\System;
use Livewire\Volt\Component;

new class extends Component {
    public string $logo = '';
    public string $brand = '';
    public bool $showBrand = true;

    public function mount()
    {
        $this->loadBrand();
    }

    protected function loadBrand()
    {
        $system = System::first();
        $this->brand = $system->app_name ?? '';
        $this->logo = $system->app_logo ? asset('/storage/' . $system->app_logo) : (asset('img/logo.png') ?? '');
    }
}; ?>

@volt
    <div class="flex items-center gap-4 w-fit">
        <x-app-logo :logo="$logo" class="w-4 h-4 scale-150" />
        <a wire:navigate href="{{ url('/') }}" class="font-bold text-heading text-nowrap">{{ $brand }}</a>
    </div>
@endvolt

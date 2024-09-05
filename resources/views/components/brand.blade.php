<?php

use App\Models\School;
use Livewire\Volt\Component;

new class extends Component {
    public string $logo = '';
    public string $brand = '';

    public function mount()
    {
        $this->loadBrand();
    }

    private function loadBrand()
    {
        $school = School::first();
        $this->logo = $school->logo ?? (asset('img/logo.png') ?? '');
        $this->brand = $school->name ?? '';
    }
};
?>

@volt
    <div class="flex items-center gap-2 w-fit">
        <x-application-logo :logo="$logo" class="scale-110 max-h-4" />
        <a wire:navigate href="{{ url('/') }}" class="font-bold text-heading text-nowrap">{{ $brand }}</a>
    </div>
@endvolt

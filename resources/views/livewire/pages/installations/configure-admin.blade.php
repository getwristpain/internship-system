<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.guest')] class extends Component {
    /**
     * Arahkan kembali ke langkah sebelumnya.
     */
    public function back()
    {
        return $this->redirect(route('install.step1'), navigate: true);
    }
}; ?>

<div class="pb-8 space-y-12">
    <x-nav-step backTo="Atur Sekolah" route="install.step1" step="2" finish="3" />

    <x-form-group action="next">
        <x-slot name="formHeader">
            <h1 class="text-2xl font-heading">Buat Administrator</h1>
        </x-slot>
        <x-slot name="formInput"></x-slot>
        <x-slot name="formAction">
            <x-button label="Kembali" action="back" />
            <x-button-submit label="Selanjutnya" />
        </x-slot>
    </x-form-group>
</div>

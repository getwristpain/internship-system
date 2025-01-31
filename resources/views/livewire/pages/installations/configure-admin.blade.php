<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.guest')] class extends Component {
    public function next()
    {
        return $this->redirect(route('install.finish'), navigate: true);
    }

    public function back()
    {
        return $this->redirect(route('install.step2'), navigate: true);
    }
}; ?>

<div class="pb-8 space-y-12">
    <x-nav-step backTo="Atur Jurusan" route="install.step2" step="3" finish="4" />

    <x-form-group action="next">
        <!-- Form Header --->
        <x-slot name="header">
            <h1 class="text-2xl font-heading">Buat Akun Administrator</h1>
            <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Expedita labore ipsa, doloribus voluptatem
                odit, fugiat possimus nemo, et architecto corrupti veniam numquam quae? Tempore, nobis odio
                necessitatibus aliquid non temporibus!</p>
        </x-slot>

        <!-- Form Content --->
        <x-slot name="content">
            <x-user-form />
        </x-slot>

        <!-- Form Footer --->
        <x-slot name="footer">
            <x-button action="back">Kembali</x-button>
            <x-button-submit>Buat Akun</x-button-submit>
        </x-slot>
    </x-form-group>
</div>

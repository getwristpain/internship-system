<?php

use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    public bool $showDeleteUserModal = false;

    #[On('openDeleteUserModal')]
    public function handleOpenDeleteUserModal(bool $show = false)
    {
        $this->showDeleteUserModal = $show;
    }
}; ?>

<x-modal show="showDeleteUserModal">
    <x-slot name="header">
        Delete User
    </x-slot>
    <x-slot name="content">
        // content
    </x-slot>
    <x-slot name="footer">
        // footer
    </x-slot>
</x-modal>

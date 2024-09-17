<?php

use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    public bool $show = false;

    #[On('openEditStudentModal')]
    public function handleOpenModal(bool $show = false)
    {
        $this->show = $show;
    }

    #[On('modal-closed')]
    public function handleCloseModal()
    {
        $this->show = false;
    }
}; ?>

<x-modal show="show">
    <x-slot name="header">
        // header
    </x-slot>
    <x-slot name="content">
        //content
    </x-slot>
    <x-slot name="footer">

    </x-slot>
</x-modal>

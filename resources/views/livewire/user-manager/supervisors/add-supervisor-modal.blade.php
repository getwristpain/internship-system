<?php

namespace App\Http\Livewire;

use Livewire\Attributes\On;
use Livewire\Volt\Component;
use App\Livewire\Forms\SupervisorForm;

new class extends Component {
    public SupervisorForm $form;
    public bool $show = false;

    #[On('openAddSupervisorModal')]
    public function handleOpenModal(bool $show = false)
    {
        $this->show = $show;
    }

    #[On('modal-closed')]
    public function handleCloseModal()
    {
        $this->show = false;
    }

    public function saveSupervisor()
    {
        // Clear previous errors before attempting to save
        $this->form->errors = [];

        // Attempt to create a new supervisor
        $this->form->createNewSupervisor();

        // If there are any errors, flash them and stop the execution
        if (!empty($this->form->errors)) {
            foreach ($this->form->errors as $error) {
                flash()->error($error);
            }
            return; // Do not proceed if there are errors
        }

        // Dispatch event and flash success if no errors
        $this->dispatch('supervisor-updated', supervisorId: $this->form->email);
        flash()->success('Kunci akses supervisor berhasil ditambahkan.');

        // Close modal after successful creation
        $this->handleCloseModal();
    }
};
?>

<x-modal show="show" :form="true" action="saveSupervisor">
    <x-slot name="header">
        Buat Akses Supervisor
    </x-slot>
    <x-slot name="content">
        <div class="flex flex-col gap-4">
            <x-input-text type="email" name="email" model="form.email" label="Email (Opsional)"
                placeholder="Masukan email..." />
            <x-input-text name="expiryDays" model="form.expiryDays" type="number" label="Masa Kadaluarsa"
                placeholder="Kadaluarsa" unit="Hari" required />
        </div>
    </x-slot>
    <x-slot name="footer">
        <button class="btn btn-success" type="submit">Generate</button>
    </x-slot>
</x-modal>

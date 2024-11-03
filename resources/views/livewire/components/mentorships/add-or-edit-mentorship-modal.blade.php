<?php

use App\Livewire\Forms\MentorshipForm;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    public MentorshipForm $form;

    public bool $show = false;

    #[On('add-item-action')]
    #[On('edit-item-action')]
    public function handleShowModal(?int $id = null)
    {
        $this->show = true;
        $this->form->mentorshipId = $id;
        $this->form->initMentorshipsData();
    }

    #[On('modal-closed')]
    public function handleCloseModal()
    {
        $this->show = false;
        $this->form->resetForm();
    }

    public function saveMentorship()
    {
        $this->form->saveMentorship();
        $this->handleCloseModal();
        $this->dispatch('mentorship-updated');
    }
}; ?>

<x-modal show="show" :form="true" action="saveMentorship">
    <x-slot name="header">
        {{ !empty($form->mentorshipId) ? 'Edit Bimbingan' : 'Tambah Bimbingan' }}
    </x-slot>

    <x-slot name="content">
        <div>
            <table class="table table-list">
                <tr>
                    <th>Tanggal</th>
                    <td>
                        <x-input-form required type="date" name="date" model="form.date" />
                    </td>
                </tr>
                <tr>
                    <th>Materi Bimbingan</th>
                    <td>
                        <x-input-form required type="textarea" name="content" model="form.content"
                            placeholder="Tuliskan materi bimbingan..." />
                    </td>
                </tr>
            </table>
        </div>
    </x-slot>

    <x-slot name="footer">
        <button class="btn btn-outline btn-neutral" type="button" wire:click="handleCloseModal">
            <span>Batal</span>
        </button>
        <button class="btn btn-neutral" type="submit">
            <iconify-icon icon="material-symbols:save" class="scale-125"></iconify-icon>
            <span>Simpan</span>
        </button>
    </x-slot>
</x-modal>

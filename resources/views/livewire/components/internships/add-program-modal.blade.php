<?php

use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\Attributes\Validate;

new class extends Component {
    public bool $show = true;
    public array $program = [];

    public function mount()
    {
        $this->initProgramData();
    }

    private function initProgramData()
    {
        $this->program = [
            'year' => Carbon::now()->format('Y'),
        ];
    }

    #[Validate]
    public function rules()
    {
        return [
            'program.title' => 'required|string|min:5|max:20',
            'program.year' => 'required|integer|min:2000',
            'program.date_start' => 'required|date',
            'program.date_finish' => 'required|date|after:program.date_start',
            'program.status_id' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'program.date_finish.after' => 'Tanggal selesai harus lebih dari tanggal mulai.',
        ];
    }

    public function saveProgram()
    {
        $this->validate();
    }

    #[On('close-modal')]
    #[On('open-add-internship-program-modal')]
    public function toggleModal()
    {
        $this->show = !$this->show;
    }
}; ?>

<x-modal show="show" :form="true" action="saveProgram">
    <x-slot name="header">Tambah Program</x-slot>
    <x-slot name="content">
        <div class="flex flex-col gap-4 w-full">
            <table class="table table-list">
                <tr>
                    <th class="required">Judul Program</th>
                    <td>
                        <x-input-form required type="text" model="program.title"
                            placeholder="Judul program..."></x-input-form>
                    </td>
                </tr>
                <tr>
                    <th class="required">Periode</th>
                    <td>
                        <x-input-form required type="number" name="program_year" model="program.year" min="2000"
                            step="1" width="32" unit="Tahun"></x-input-form>
                    </td>
                </tr>
                <tr>
                    <th class="required">Masa Program</th>
                    <td>
                        <x-input-range required type="date" min="2000-01-01"></x-input-range>
                    </td>
                </tr>
            </table>
        </div>
    </x-slot>
    <x-slot name="footer">

    </x-slot>
</x-modal>

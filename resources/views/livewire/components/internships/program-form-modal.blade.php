<?php

use Carbon\Carbon;
use App\Models\Program;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Log;
use App\Services\EventStatusService;

new class extends Component {
    public bool $show = false;
    public array $program = [];

    public function mount()
    {
        // Inisiasi data program
        $this->initProgramData();
    }

    private function initProgramData(?int $programId = null)
    {
        // Cek apakah $programId memiliki nilai
        if (!$programId) {
            // Kembalikan nilai default
            $this->program = [
                'title' => '',
                'year' => '',
                'date_start' => '',
                'date_finish' => '',
            ];
            return;
        }

        // Ambil data program jika tersedia
        $programData = Program::find($programId);
        if (!$programData) {
            flash()->info('Program tidak ditemukan');
            return;
        }

        // Kembalikan data program dalam array.
        $this->program = $programData->toArray();
        return;
    }

    #[On('open-program-form-modal')]
    public function openModal(?int $programId = null)
    {
        // Inisiasi data program
        $this->initProgramData($programId);

        // Buka modal
        $this->show = !$this->show;
    }

    #[On('close-modal')]
    public function closeModal()
    {
        $this->reset(['program']);
        $this->show = !$this->show;
    }

    #[Validate]
    public function rules()
    {
        return [
            'program.title' => 'required|string|min:5|max:50|unique:programs,title',
            'program.year' => 'required|integer|min:2000',
            'program.date_start' => 'required|date',
            'program.date_finish' => 'required|date|after:program.date_start',
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

        try {
            // Update atau buat program baru
            Program::updateOrCreate(['id' => $this->program['id']], $this->prepProgramData());

            // Dispatch event dan pesan sukses
            $this->dispatch('program-updated');
            flash()->success('Program berhasil disimpan.');
        } catch (\Throwable $th) {
            // Log error dengan informasi detail
            Log::error('Failed to create program', [
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
            ]);

            // Tampilkan pesan error ke pengguna
            flash()->error('Gagal menyimpan program!');
        }

        $this->closeModal();
        return;
    }

    private function prepProgramData()
    {
        // Validasi apakah data program tersedia
        if (!isset($this->program)) {
            throw new \Exception('Program data is missing.');

            flash()->info('Data program tidak lengkap.');
            return;
        }

        // Ambil status 'pending'
        $status = EventStatusService::setStatus(Carbon::parse($this->program['date_start']), Carbon::parse($this->program['date_finish']));
        if (!$status) {
            throw new \Exception('Status not found.');

            flash()->info('Status tidak ditemukan');
            return;
        }

        // Siapkan data program
        return [
            'title' => $this->program['title'] ?? null,
            'year' => $this->program['year'] ?? null,
            'date_start' => $this->program['date_start'] ?? null,
            'date_finish' => $this->program['date_finish'] ?? null,
            'status_id' => $status->id,
        ];
    }
}; ?>

<x-modal show="show" :form="true" action="saveProgram">
    <x-slot name="header">Tambah Program</x-slot>
    <x-slot name="content">
        <div class="flex flex-col w-full gap-4">
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
                            step="1" width="w-32" unit="Tahun"></x-input-form>
                    </td>
                </tr>
                <tr>
                    <th class="required">Masa Program</th>
                    <td>
                        <x-input-range required type="date" min="2000-01-01" minLabel="Tanggal Mulai"
                            maxLabel="Tanggal Selesai" minModel="program.date_start"
                            maxModel="program.date_finish"></x-input-range>
                    </td>
                </tr>
            </table>
        </div>
    </x-slot>
    <x-slot name="footer">
        <x-button-cancel label="Batal" action="closeModal"></x-button-cancel>
        <x-button-submit label="Simpan"></x-button-submit>
    </x-slot>
</x-modal>

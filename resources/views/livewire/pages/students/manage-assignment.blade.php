<?php

use Carbon\Carbon;
use App\Helpers\FileHelper;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use App\Models\InternshipReport;
use Illuminate\Http\UploadedFile;
use Livewire\Attributes\Validate;
use App\Helpers\StatusBadgeMapper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\InternshipReportService;

new #[Layout('layouts.app')] class extends Component {
    use WithFileUploads;

    public ?UploadedFile $internshipReportFile = null;
    public array $tempFile = [];

    #[Validate]
    public function rules()
    {
        return [
            'internshipReportFile' => 'file|max:10240|mimes:pdf,doc,docx',
            'tempFile.filename' => 'required|string|min:10|max:255',
        ];
    }

    public function updatedInternshipReportFile()
    {
        $validation = $this->validateOnly('internshipReportFile');

        if ($validation) {
            $this->tempFile['filename'] = pathinfo($this->internshipReportFile->getClientOriginalName(), PATHINFO_FILENAME);
            $this->tempFile['size'] = FileHelper::formatFileSize($this->internshipReportFile->getSize());
            $this->tempFile['extention'] = $this->internshipReportFile->getClientOriginalExtension();
        }
    }

    public function resetInternshipReportFile()
    {
        $this->reset(['tempFile', 'internshipReportFile']);
    }

    public function sendInternshipReport()
    {
        $this->validateOnly('tempFile.filename');
        $filePath = FileHelper::storeDoc($this->internshipReportFile);

        if (!$filePath) {
            flash()->error('Gagal mengirim berkas.');
            return;
        }

        $store = InternshipReport::create([
            'user_id' => Auth::id(),
            'file_name' => $this->tempFile['filename'] . '.' . $this->tempFile['extention'],
            'file_path' => $filePath,
        ]);

        if (!$store) {
            FileHelper::deleteFile($filePath);
            flash()->error('Gagal mengirim berkas.');
            return;
        }

        flash()->success('Berkas laporan PKL berhasil terkirim.');
        $this->dispatch('internship-report-updated');
        $this->resetInternshipReportFile();
    }

    private function prepareInternshipReportsData()
    {
        $reports = InternshipReportService::getReports(Auth::id());

        $data = $reports->map(function ($report) {
            return [
                'file_name' => $report->file_name ?? '',
                'file_path' => $report->file_path ?? '',
                'submitted_at' => Carbon::parse($report->submitted_at)->translatedFormat('d M Y, H:i T') ?? '',
                'status_name' => __('status.acceptance.' . $report->status->name) ?? 'N/A',
                'status_class' => StatusBadgeMapper::getStatusBadgeClass($report->status->name),
                'remarks' => $report->remarks ?? '',
                'file_url' => Storage::url($report->file_path) ?? '',
            ];
        });

        return $data;
    }

    public function with()
    {
        return [
            'reports' => $this->prepareInternshipReportsData(),
        ];
    }
}; ?>

<div class="w-full h-full">
    <x-card class="h-full">
        <x-slot name="heading">Laporan PKL</x-slot>
        <x-slot name="content">
            <div class="flex flex-col gap-4">
                <div class="p-4 border border-yellow-500 rounded-md bg-yellow-50">
                    <p>
                        Pastikan file yang diunggah dalam format <b>PDF, DOC, atau DOCX</b> dengan penamaan:
                        <b><i>NamaLengkap_Kelas_Laporan_PKL</i></b>, dengan maksimal ukuran <b>10 MB</b>. Laporan
                        harus lengkap dengan halaman sampul, kata pengantar, daftar isi, serta isi laporan sesuai format
                        yang ditentukan.
                    </p>
                </div>

                <div class="flex flex-col gap-4">
                    <div>
                        <table class="table">
                            <tr>
                                <th>Laporan</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Komentar</th>
                            </tr>
                            @forelse ($reports as $item)
                                <tr>
                                    <td>
                                        <a href="{{ $item['file_url'] ?? '#' }}" target="__blank"
                                            class="flex gap-2 items-center text-blue-700">
                                            <iconify-icon icon="quill:attachment"
                                                class="scale-125 opacity-70"></iconify-icon>
                                            <span>{{ $item['file_name'] }}</span>
                                        </a>
                                    </td>
                                    <td>{{ $item['submitted_at'] }}</td>
                                    <td>
                                        <span class="{{ $item['status_class'] }}">
                                            {{ $item['status_name'] }}
                                        </span>
                                    </td>
                                    <td>{{ $item['remarks'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">
                                        <p>Belum ada laporan yang diunggah.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </table>
                    </div>

                    <div
                        class="flex justify-center p-8 border border-dashed border-blue-500 rounded-md bg-blue-50 bg-opacity-40">
                        @if (empty($tempFile))
                            <div class="flex flex-col items-center gap-4">
                                <x-input-file name="internship_report" model="internshipReportFile"
                                    placeholder="Unggah Laporan" class="bg-white" :hideLabel="true" />

                                <x-input-error :messages="$errors->get('internshipReportFile')" class="mt-2 text-red-500" />
                            </div>
                        @else
                            <div class="flex gap-12 justify-between items-center w-full">
                                <div class="flex-1 flex gap-8 items-center">
                                    <x-input-form type="text" model="tempFile.filename" required />
                                    <span>{{ $tempFile['size'] }}</span>
                                </div>
                                <div class="flex gap-2">
                                    <button class="btn btn-sm btn-error btn-outline"
                                        wire:click="resetInternshipReportFile" wire:target="sendInternshipReport"
                                        wire:loading.class="disabled" wire:loading.attr="disabled">
                                        <iconify-icon icon="mdi:delete"></iconify-icon>
                                    </button>
                                    <button class="btn btn-sm btn-neutral" wire:click="sendInternshipReport"
                                        wire:target="sendInternshipReport" wire:loading.class="disabled"
                                        wire:loading.attr="disabled">
                                        <iconify-icon icon="carbon:send-alt-filled"></iconify-icon>
                                        <span class="hidden md:inline-block">Kirim</span>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </x-slot>
    </x-card>
</div>

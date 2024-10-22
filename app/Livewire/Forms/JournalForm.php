<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\Journal;
use App\Helpers\FileHelper;
use Illuminate\Http\UploadedFile;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class JournalForm extends Form
{
    public string $date;
    public string $time_start;
    public string $time_finish;
    public string $attendance = 'attendance-status-present';
    public string $activity = '';
    public ?UploadedFile $attachment = null;
    public string $remarks = '';

    #[Validate]
    protected function rules(): array
    {
        $rules = [
            'date'        => 'required|date',
            'time_start'  => 'required|string',
            'time_finish' => 'required|string',
            'attendance'  => 'required|string',
            'activity'    => 'nullable|string|max:255',
            'attachment'  => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'remarks'     => 'nullable|string|max:255',
        ];

        if ($this->attendance !== 'attendance-status-holiday') {
            $rules = array_merge($rules, [
                'activity'  => 'required|string|max:255',
                'attachment' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            ]);
        }

        return $rules;
    }

    public function saveJournal()
    {
        $this->validate();
        Journal::create($this->prepareJournalData());
    }

    /**
     * Persiapkan data jurnal untuk disimpan
     *
     * @return array
     */
    protected function prepareJournalData(): array
    {
        return [
            'user_id'     => Auth::id() ?? '',
            'date'        => $this->date ?? '',
            'time_start'  => $this->time_start ?? '',
            'time_finish' => $this->time_finish ?? '',
            'attendance'   => $this->attendance ?? '',
            'activity'    => $this->activity ?? '',
            'attachment'  => $this->handleAttachment() ?? '',
            'remarks'     => $this->remarks ?? '',
        ];
    }


    /**
     * Process the attachment file
     *
     * @return string|null
     */
    protected function handleAttachment(): ?string
    {
        if (!$this->attachment) {
            return null;
        }

        // Convert and compress the image to WEBP format
        $filePath = FileHelper::storeAsWebp($this->attachment);

        return Storage::url($filePath);
    }
}

<?php

namespace App\Livewire\Forms;

use Carbon\Carbon;
use Livewire\Form;
use App\Models\Journal;
use App\Helpers\FileHelper;
use App\Services\JournalService;
use Illuminate\Http\UploadedFile;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class JournalForm extends Form
{
    public ?int $journalId = null;
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
            'date' => 'required|date',
            'time_start' => 'required|string',
            'time_finish' => 'required|string',
            'attendance' => 'required|string',
            'activity' => 'nullable|string|max:255',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'remarks' => 'nullable|string|max:255',
        ];

        // Adding specific rules if attendance is not holiday
        if ($this->attendance !== 'attendance-status-holiday') {
            $rules['activity'] = 'required|string|max:255';
            $rules['attachment'] = 'required|file|mimes:jpg,jpeg,png,pdf|max:2048';
        }

        return $rules;
    }

    public function resetForm()
    {
        $this->date = Carbon::now()->format('Y-m-d');
        $this->time_start = '08:00';
        $this->time_finish = '16:00';
        $this->attendance = 'attendance-status-present';
        $this->activity = '';
        $this->attachment = null;
        $this->remarks = '';
    }

    public function initJournalData()
    {
        $journalData = JournalService::findJournal($this->journalId);

        if ($journalData) {
            $this->loadJournalData($journalData);
        } else {
            $this->setDefaultTimeFromSession();
        }
    }

    private function setDefaultTimeFromSession()
    {
        $this->time_start = session('journal_time_start', '08:00');
        $this->time_finish = session('journal_time_finish', '16:00');
    }

    private function loadJournalData($journalData)
    {
        $this->date = $journalData->date ?? $this->date;
        $this->time_start = $journalData->time_start ?? $this->time_start;
        $this->time_finish = $journalData->time_finish ?? $this->time_finish;
        $this->attendance = $journalData->attendance ?? $this->attendance;
        $this->activity = $journalData->activity ?? $this->activity;
        $this->attachment = $this->retrieveAttachment($journalData->attachment) ?? $this->attachment;
        $this->remarks = $journalData->remarks ?? $this->remarks;
    }

    public function retrieveAttachment(?string $path): ?UploadedFile
    {
        if (!$path) {
            return null;
        }

        $filePath = public_path($path);
        return new UploadedFile($filePath, basename($filePath));
    }

    public function saveJournal()
    {
        $this->validate();

        // Use update or create to support both add and edit modes
        Journal::updateOrCreate(
            ['id' => $this->journalId],
            $this->prepareJournalData()
        );
    }

    protected function prepareJournalData(): array
    {
        return [
            'user_id' => Auth::id() ?? '',
            'date' => $this->date,
            'time_start' => $this->time_start,
            'time_finish' => $this->time_finish,
            'attendance' => $this->attendance,
            'activity' => $this->activity,
            'attachment' => $this->processAttachment(),
            'remarks' => $this->remarks,
        ];
    }

    protected function processAttachment(): ?string
    {
        if (!$this->attachment) {
            return null;
        }

        $filePath = FileHelper::storeAsWebp($this->attachment);
        return Storage::url($filePath);
    }
}

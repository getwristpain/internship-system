<?php

namespace App\Livewire\Forms;

use Carbon\Carbon;
use Livewire\Form;
use App\Models\Mentorship;
use Livewire\Attributes\Validate;
use App\Services\MentorshipService;
use Illuminate\Support\Facades\Auth;

class MentorshipForm extends Form
{
    public ?int $mentorshipId = null;
    public ?string $date = null;
    public ?string $content = null;

    #[Validate]
    public function rules()
    {
        return [
            'date' => 'required|date',
            'content' => 'required|string|min:10|max:750',
        ];
    }

    public function resetForm()
    {
        $this->reset(['mentorshipId', 'date', 'content']);
        $this->resetValidation();
        $this->initMentorshipsData();
    }

    public function initMentorshipsData()
    {
        $data = MentorshipService::findMentorship($this->mentorshipId);

        $this->date = $data->date ?? Carbon::now()->format('Y-m-d');
        $this->content = $data->content ?? $this->content;
    }

    public function saveMentorship()
    {
        $this->validate();

        Mentorship::updateOrCreate(
            ['id' => $this->mentorshipId],
            $this->prepareMentorshipData()
        );
    }

    private function prepareMentorshipData()
    {
        return [
            'user_id' => Auth::id() ?? '',
            'date' => $this->date ?? Carbon::now(),
            'content' => $this->content ?? '',
        ];
    }
}

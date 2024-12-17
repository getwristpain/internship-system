<?php

use App\Models\User;
use App\Services\UserService;
use App\Helpers\NoticeAlert;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    public bool $showAlerts = true;
    public array $alerts = [];

    public function mount(): void
    {
        $this->checkForAlerts();
        $this->handleShowAlerts();
    }

    private function handleShowAlerts(): void
    {
        $user = auth()->user();

        if (empty($this->alerts) && !$user->hasVerifiedEmail() && !$user->hasRole(['student', 'teacher'])) {
            $this->showAlerts = false;
        } elseif (empty($this->alerts) && $user->hasVerifiedEmail()) {
            $this->showAlerts = false;
        }
    }

    private function checkForAlerts(): void
    {
        // Using NoticeAlert to get and remove duplicate alerts
        $noticeAlert = new NoticeAlert(Auth::user());
        $this->alerts = $noticeAlert->getAlerts();

        // cek apakah alerts punya array dengan slug "user-email-verification"
    }
};
?>

<div class="flex flex-col col-span-4 space-y-4 {{ $showAlerts ?: 'hidden' }}">
    @role('student|teacher')
        @livewire('components.widgets.verify-email-alert')
    @endrole

    @if ($alerts)
        @foreach ($alerts as $alert)
            <x-alert-box type="{{ $alert['type'] }}" message="{{ $alert['message'] }}" label="{{ $alert['label'] }}"
                action="{{ $alert['action'] }}" />
        @endforeach
    @endif
</div>

<?php

use App\Models\User;
use App\Utils\Alert;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    public array $user = [];
    public array $userProfile = [];
    public string $userRole = 'guest';
    public string $userStatus = 'pending';
    public array $alerts = [];

    public function mount(): void
    {
        $this->loadUserData();
        $this->checkForAlerts();
    }

    protected function loadUserData(): void
    {
        $user = User::with(['profile', 'roles', 'status'])->find(Auth::id());

        if ($user) {
            $this->user = $user->toArray();
            $this->userProfile = $user->profile ? $user->profile->toArray() : [];
            $this->userRole = $user->roles->first()->name ?? 'guest';
            $this->userStatus = $user->status->name ?? 'pending';
        }
    }

    protected function checkForAlerts(): void
    {
        $alertHelper = new Alert(Auth::user(), $this->userRole, $this->userProfile);
        $this->alerts = $this->removeDuplicateAlerts($alertHelper->getAlerts());
    }

    protected function removeDuplicateAlerts(array $alerts): array
    {
        $seenMessages = [];
        return array_filter($alerts, function ($alert) use (&$seenMessages) {
            if (in_array($alert['message'], $seenMessages)) {
                return false; // Skip this alert if the message is already seen
            }
            $seenMessages[] = $alert['message']; // Mark this message as seen
            return true; // Keep this alert
        });
    }
};

?>
<div class="flex flex-col col-span-4 space-y-4">
    @role('student|teacher')
        @livewire('widgets.verify-email-alert')
    @endrole

    @if ($alerts)
        @foreach ($alerts as $alert)
            <x-alert-box type="{{ $alert['type'] }}" message="{{ $alert['message'] }}" route="{{ $alert['route'] }}"
                label="{{ $alert['label'] }}" />
        @endforeach
    @endif
</div>

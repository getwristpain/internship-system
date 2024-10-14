<?php

use App\Models\User;
use App\Services\UserService;
use App\Helpers\UserAlert;
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
        $user = UserService::findUser(Auth::id());

        if ($user) {
            $this->user = $user->toArray();
            $this->userProfile = $user->profile ? $user->profile->toArray() : [];
            $this->userRole = $user->roles->first()->name ?? 'guest';
            $this->userStatus = $user->status->name ?? 'pending';
        }
    }

    protected function checkForAlerts(): void
    {
        // Using UserAlert to get and remove duplicate alerts
        $userAlerts = new UserAlert(Auth::user(), $this->userRole, $this->userProfile);
        $this->alerts = UserAlert::removeDuplicateAlerts($userAlerts->getAlerts());
    }
};
?>

<div class="flex flex-col col-span-4 space-y-4">
    @role('student|teacher')
        @livewire('components.widgets.verify-email-alert')
    @endrole

    @if ($alerts)
        @foreach ($alerts as $alert)
            <x-alert-box type="{{ $alert['type'] }}" message="{{ $alert['message'] }}" route="{{ $alert['route'] }}"
                label="{{ $alert['label'] }}" />
        @endforeach
    @endif
</div>

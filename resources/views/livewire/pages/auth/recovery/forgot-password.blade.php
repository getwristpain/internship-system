<?php

use App\Models\User;
use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): bool
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        $user = User::where('email', $this->email)->first();

        if ($user?->hasRole('supervisor')) {
            $this->addError('email', __('Supervisor tidak diizinkan untuk mereset password.'));
            return false;
        }

        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status !== Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));
            return false;
        }

        $this->reset('email');
        session()->flash('status', __($status));
        return true;
    }

    /**
     * Redirect the user to the login page.
     */
    public function redirectToLogin(): void
    {
        $this->redirect(route('login'), navigate: true);
    }
};

?>

<div class="flex flex-col max-w-md gap-8 mx-auto">
    <div class="text-center">
        <h1 class="text-2xl font-heading">Lupa Password</h1>
        <p>Link reset password akan dikirim melalui email.</p>
    </div>

    <form wire:submit.prevent="sendPasswordResetLink" class="flex flex-col gap-8">
        <div class="flex flex-col gap-4">
            <x-session-flash-status></x-session-flash-status>
            <x-input-form type="email" name="email" model="email" placeholder="Email" required autofocus />
        </div>
        <div class="flex items-center justify-end space-x-4">
            <x-button label="Kembali" action="redirectToLogin"></x-button>
            <x-button-submit label="Kirim"></x-button-submit>
        </div>
    </form>
</div>

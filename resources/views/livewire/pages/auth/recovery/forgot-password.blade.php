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
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        // Check if the user is a supervisor
        $user = User::where('email', $this->email)->first();

        if ($user && $user->hasRole('supervisor')) {
            $this->addError('email', __('Supervisor tidak diizinkan untuk mereset password.'));
            return;
        }

        $status = Password::sendResetLink($this->only('email'));

        if ($status !== Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));
            return;
        }

        $this->reset('email');
        session()->flash('status', __($status));
    }

    public function back()
    {
        return $this->redirect(route('login'), navigate: true);
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
            <x-input-text type="email" name="email" model="email" placeholder="Email" required autofocus />
            <x-input-session-status></x-input-session-status>
        </div>
        <div class="flex items-center justify-end space-x-4">
            <button type="button" class="btn btn-outline btn-neutral" wire:click="back">Kembali</button>
            <button type="submit" class="btn btn-neutral">Kirim</button>
        </div>
    </form>
</div>

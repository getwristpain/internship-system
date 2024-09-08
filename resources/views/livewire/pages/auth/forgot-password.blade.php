<?php

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

        $status = Password::sendResetLink($this->only('email'));

        if ($status != Password::RESET_LINK_SENT) {
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
}; ?>

<div class="flex flex-col max-w-md mx-auto space-y-12">
    <div class="text-center">
        <h1 class="text-2xl font-heading">Lupa Password</h1>
        <p>Link reset password akan dikirim melalui email.</p>
    </div>

    <form wire:submit.prevent="sendPasswordResetLink" class="flex flex-col space-y-12">
        <div class="space-y-4">
            <x-input-text type="email" name="email" model="email" placeholder="Email" required autofocus />
        </div>
        <div class="flex items-center justify-end space-x-4">
            <button class="btn btn-outline btn-neutral" wire:click="back">Kembali</button>
            <button class="btn btn-neutral" wire:click="sendPasswordResetLink">Kirim</button>
        </div>
    </form>
</div>

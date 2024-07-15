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

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink($this->only('email'));

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>

<div class="flex flex-col gap-5 p-4 py-10 items-center justify-center h-screen sm max-w-sm self-center">
    <div class="mb-4">
        <h1 class="font-heading text-lg text-center">
            {{ __('Lupa Password') }}
        </h1>
        <p>
            Link reset password akan dikirim melalui email.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="sendPasswordResetLink" class="flex flex-col gap-5 w-full">
        <!-- Email Address -->
        <div>
            <x-text-input wire:model="email" id="email" class="block w-full" type="email" name="email"
                placeholder="Email" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center self-center gap-2">
            <x-primary-button>
                {{ __('Reset Password') }}
            </x-primary-button>
            <x-secondary-button onclick="window.history.back()">
                {{__('Batal')}}
            </x-secondary-button>
        </div>
    </form>
</div>

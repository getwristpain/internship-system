<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    #[Locked]
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Mount the component.
     */
    public function mount(string $token): void
    {
        $this->token = $token;

        $this->email = request()->string('email');
    }

    /**
     * Reset the password for the given user.
     */
    public function resetPassword(): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset($this->only('email', 'password', 'password_confirmation', 'token'), function ($user) {
            $user
                ->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])
                ->save();

            event(new PasswordReset($user));
        });

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        if ($status != Password::PASSWORD_RESET) {
            $this->addError('email', __($status));
            return;
        }

        session()->flash('status', __($status));
        return redirect(route('login'), navigate: true)->with('status', 'Password berhasil diperbarui, masuk untuk melanjutkan');
    }

    public function back()
    {
        return $this->redirect(route('login'), navigate: true);
    }
}; ?>

<div class="flex flex-col w-full max-w-md mx-auto space-y-12">

    <!-- Heading -->
    <div class="flex flex-col w-full gap-2 text-center">
        <h1 class="text-xl font-heading">Buat Password Baru</h1>
        <p>Reset passwordmu dan jangan sampai lupa.</p>
    </div>

    <form wire:submit="resetPassword" class="flex flex-col w-full gap-4">
        <div class="flex flex-col w-full space-y-4">
            <x-input-text disabled type="email" name="email" model="email" placeholder="Email" required autofocus />
            <x-input-text type="password" name="password" model="password" placeholder="Password" required />
            <x-input-text type="password" name="password_confirmation" model="password_confirmation"
                Placeholder="Konfirmasi Password" required />
            <x-input-session-status></x-input-session-status>
        </div>

        <div class="flex items-center justify-end w-full space-x-4">
            <button type="button" wire:click="back" class="btn btn-outline btn-neutral">Batal</button>
            <button type="submit" class="btn btn-neutral">Reset Password</button>
        </div>
    </form>
</div>

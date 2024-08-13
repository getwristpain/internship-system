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

        Session::flash('status', __($status));

        $this->redirectRoute('login', navigate: true);
    }
}; ?>

<div class="w-full h-full flex justify-center items-center p-8 lg:pb-24">
    <div class="flex flex-col gap-16 w-full max-w-md">

        <!-- Heading -->
        <div class="flex flex-col gap-2 text-center my-5 w-full">
            <h1 class="font-heading text-xl">Buat Password Baru</h1>
            <p>Reset passwordmu dan jangan sampai lupa lagi.</p>
        </div>

        <form wire:submit="resetPassword" class="flex flex-col gap-16 w-full">
            <div class="flex flex-col gap-4 w-full">
                <!-- Email Address -->
                <div class="w-full">
                    <x-input-text type="email" name="email" model="email" label="Email" required autofocus />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="w-full">
                    <x-input-text type="password" name="password" model="password" label="Password" required />
                </div>

                <!-- Confirm Password -->
                <div class="w-full">
                    <x-input-text type="password" name="password_confirmation" model="password_confirmation"
                        label="Konfirmasi Password" required autofocus />
                </div>
            </div>

            <div class="flex items-center justify-center w-full gap-2">
                <x-button-secondary onclick="window.history.back()">
                    {{ __('Batal') }}
                </x-button-secondary>

                <x-button-primary type="submit">
                    {{ __('Reset Password') }}
                </x-button-primary>
            </div>
        </form>
    </div>
</div>

<?php

namespace App\Livewire\Forms;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Form;

class LoginForm extends Form
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    public array $errors = [];

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        // Validate input fields
        $this->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember' => 'boolean',
        ]);

        $this->errors = [];
        $this->ensureIsNotRateLimited();

        // Check if the user exists
        $user = Auth::getProvider()->retrieveByCredentials(['email' => $this->email]);

        if (!$user) {
            RateLimiter::hit($this->throttleKey());
            $this->errors['email'] = __('auth.user_not_found'); // Set error message for user not found
            return;
        }

        // Attempt authentication
        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());
            $this->errors['password'] = __('auth.password_incorrect'); // Set error message for incorrect password
            return;
        }

        RateLimiter::clear($this->throttleKey());

        session()->regenerate();
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        $request = new Request;
        event(new Lockout($request));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        $this->errors['email'] = __('auth.throttle', [
            'seconds' => $seconds,
            'minutes' => ceil($seconds / 60),
        ]);

        throw ValidationException::withMessages([
            'email' => $this->errors['email'],
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        $request = new Request;
        return Str::transliterate(Str::lower($this->email) . '|' . $request->ip());
    }
}

<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\User;
use App\Helpers\AccessKeyGen;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginForm extends Form
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;
    public string $accessKey = '';

    /**
     * Attempt to log the user in with email and password.
     *
     * @return \Illuminate\Http\RedirectResponse|null
     */
    public function attemptLogin()
    {
        // Throttle login attempts
        $this->ensureNotRateLimited();

        try {
            $this->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
                'remember' => 'boolean',
            ]);

            if (!$this->isUserValid()) {
                return;
            }

            if (!$this->authenticateUser()) {
                return;
            }

            // Successful login, reset the rate limiter
            RateLimiter::clear($this->throttleKey());

            return redirect()->intended()->route('dashboard');
        } catch (\Exception $e) {
            $this->handleException($e, 'Failed to attempt login.');
        }
    }

    /**
     * Attempt to log the user in with an access key.
     *
     * @param string $accessKey
     * @return \Illuminate\Http\RedirectResponse|null
     */
    public function attemptLoginWithKey(string $accessKey = '')
    {
        try {
            $this->accessKey = $accessKey ?: $this->accessKey;

            $this->validate([
                'accessKey' => 'required|string',
            ]);

            $accessKeyRecord = AccessKeyGen::verifyKey($this->accessKey);

            if (!$accessKeyRecord) {
                $this->addError('accessKey', __('Kunci akses tidak valid atau sudah kadaluarsa.'));
                return;
            }

            $user = $accessKeyRecord->user;

            if (!$user || !$user->hasRole('supervisor')) {
                $this->addError('accessKey', __('Pengguna ini bukan supervisor.'));
                return;
            }

            Auth::login($user, $this->remember);

            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            $this->handleException($e, 'Failed to login with access key.');
        }
    }

    /**
     * Checks if the user exists and adds an error if not.
     *
     * @return bool
     */
    protected function isUserValid(): bool
    {
        if (!$this->userExists()) {
            $this->addError('email', __('auth.user_not_found'));
            return false;
        }

        return true;
    }

    /**
     * Verifies if the user exists in the database.
     *
     * @return bool
     */
    protected function userExists(): bool
    {
        return User::where('email', $this->email)->exists();
    }

    /**
     * Attempts to authenticate the user and adds an error if authentication fails.
     *
     * @return bool
     */
    protected function authenticateUser(): bool
    {
        if (!Auth::attempt([
            'email' => $this->email,
            'password' => $this->password
        ], $this->remember)) {
            $this->addError('password', __('auth.password_incorrect'));
            return false;
        }

        return true;
    }

    /**
     * Handle exception, log the error and save flash message.
     *
     * @param \Exception $exception
     * @param string $context
     * @return void
     */
    protected function handleException(\Exception $exception, string $context): void
    {
        Session::flash('error', __('auth.login_failed'));
        Log::error($context, ['error' => $exception->getMessage(), 'trace' => $exception->getTraceAsString()]);
    }

    /**
     * Throttles login attempts to prevent brute-force attacks.
     *
     * @throws \Illuminate\Validation\ValidationException
     * @return void
     */
    protected function ensureNotRateLimited(): void
    {
        if (RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            Session::flash('warning', __('auth.throttle'));
            throw ValidationException::withMessages([
                'email' => __('auth.throttle'),
            ]);
        }

        RateLimiter::hit($this->throttleKey(), 60);
    }

    /**
     * Get the throttle key for the current login attempt.
     *
     * @return string
     */
    protected function throttleKey(): string
    {
        return 'login:' . request()->ip();
    }
}

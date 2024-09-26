<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\User;
use App\Models\AccessKey;
use Illuminate\Support\Facades\Auth;

class LoginForm extends Form
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;
    public string $accessKey = '';

    public function attemptLogin()
    {
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

        return redirect()->intended()->route('dashboard');
    }

    public function attemptLoginWithKey(string $accessKey = '')
    {
        $this->validate([
            'accessKey' => 'required|string',
        ]);

        $this->accessKey = !$accessKey ?: $accessKey;

        $accessKeyRecord = AccessKey::where('hashed_key', $this->accessKey)
            ->where('expires_at', '>=', now())
            ->first();

        if (!$accessKeyRecord) {
            $this->addError('accessKey', __('Kunci akses tidak valid atau sudah kadaluarsa.'));
            return;
        }

        $user = $accessKeyRecord->user;

        if (!$user || !$user->hasRole('supervisor')) {
            $this->addError('accessKey', __('Pengguna ini bukan supervisor.'));
            return;
        }

        // Login user
        Auth::login($user, $this->remember);

        // Redirect ke halaman dashboard
        return redirect()->route('dashboard');
    }

    /**
     * Checks if the user exists and adds an error if not.
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
     */
    protected function userExists(): bool
    {
        return User::where('email', $this->email)->exists();
    }

    /**
     * Attempts to authenticate the user and adds an error if authentication fails.
     */
    protected function authenticateUser(): bool
    {
        if (!Auth::attempt([
            'email' => $this->email,
            'password' => $this->password
        ], $this->remember)) {
            $this->addError('email', __('auth.failed'));
            return false;
        }

        return true;
    }
}

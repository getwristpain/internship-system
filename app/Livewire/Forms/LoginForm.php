<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginForm extends Form
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    public function attemptLogin()
    {
        $this->validate();

        if (!$this->isUserValid()) {
            return;
        }

        if (!$this->authenticateUser()) {
            return;
        }

        return redirect()->intended()->route('dashboard');
    }

    public function rules(): array
    {
        return [
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember' => 'boolean',
        ];
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

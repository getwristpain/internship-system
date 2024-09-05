<?php

namespace App\Livewire\Forms;

use Livewire\Component;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginForm extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    public function attemptLogin()
    {
        $this->validateLoginInputs();

        try {
            $this->loginUser();
            session()->flash('success', 'Login successful!');
            return redirect()->intended(Fortify::redirects('login'));
        } catch (ValidationException $e) {
            $this->addError('email', 'These credentials do not match our records.');
        }
    }

    protected function validateLoginInputs()
    {
        $this->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember' => 'boolean',
        ]);
    }

    protected function loginUser()
    {
        if (!Auth::attempt([
            'email' => $this->email,
            'password' => $this->password
        ], $this->remember)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }
    }
}

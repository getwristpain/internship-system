<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\User;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class RegisterForm extends Form
{
    public User $user;
    public string $account_type = '';
    public string $email = '';
    public string $name = '';
    public string $password = '';
    public string $password_confirmation = '';
    public array $profile = [];

    public function rules()
    {
        return [
            'account_type' => 'required|in:student,teacher',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'string', 'min:8', 'confirmed', Rules\Password::defaults()],
        ];
    }

    public function handleStepOne()
    {
        $this->validate();

        try {
            $this->registeringUser();
            // return redirect()->route('register.steptwo');
            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            Log::error('User registration failed: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while registering. Please try again.');
        }
    }

    public function handleStepTwo()
    {
        // Handle step two if needed
    }

    protected function registeringUser()
    {
        $this->user = $this->createNewUser();
        Auth::login($this->user);
    }

    protected function createNewUser(): User
    {
        // Get or create Role
        $role = Role::firstOrCreate([
            'name' => $this->account_type,
        ]);

        // Create the user
        $user = User::create([
            'email' => $this->email,
            'name' => $this->name,
            'password' => Hash::make($this->password),
        ]);

        // Assign role to the user
        $user->assignRole($role);

        return $user;
    }
}

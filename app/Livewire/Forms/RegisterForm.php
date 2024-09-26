<?php

namespace App\Livewire\Forms;

use Exception;
use Livewire\Form;
use App\Models\User;
use App\Models\Status;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterForm extends Form
{
    public User $user;
    public string $account_type = '';
    public string $email = '';
    public string $name = '';
    public string $password = '';
    public string $password_confirmation = '';
    public array $profile = [];

    /**
     * Handle the first step of registration.
     *
     * @param string $account_type
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function handleStepOne(string $account_type)
    {
        $this->account_type = $account_type;

        // Validate the form data
        $this->validate([
            'account_type' => 'required|in:student,teacher',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'string', 'min:8', 'confirmed', Rules\Password::defaults()],
        ]);

        try {
            $this->registeringUser();
            return redirect()->route('dashboard');
        } catch (Exception $e) {
            Log::error('User registration failed: ' . $e->getMessage());
            flash()->error('An error occurred while registering. Please try again.');
        }
    }

    /**
     * Register a new user and log them in.
     *
     * @throws Exception
     */
    protected function registeringUser(): void
    {
        $this->user = $this->createNewUser();
        Auth::login($this->user);
        // For debugging purposes, you may want to log the user instead of dd() in production
        Log::info('New user registered and logged in: ', ['user' => $this->user]);
    }

    /**
     * Create a new user and assign a role.
     *
     * @return User
     */
    protected function createNewUser(): User
    {
        $role = $this->getRoleForAccountType($this->account_type);
        $status = Status::where(['name' => 'pending'])->first();

        $user = User::create([
            'email' => $this->email,
            'name' => $this->name,
            'password' => Hash::make($this->password),
            'status_id' => $status->id,
        ]);

        $user->assignRole($role);

        return $user;
    }

    /**
     * Get or create a role based on the account type.
     *
     * @param string $account_type
     * @return Role
     */
    protected function getRoleForAccountType(string $account_type): Role
    {
        return Role::firstOrCreate(['name' => $account_type]);
    }
}

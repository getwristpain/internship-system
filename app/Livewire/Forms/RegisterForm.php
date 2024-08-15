<?php

namespace App\Livewire\Forms;

use App\Models\User;
use Spatie\Permission\Models\Role;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules;

use Livewire\Form;

class RegisterForm extends Form
{
    public string $accountType = '';
    public string $email = '';
    public string $name = '';
    public string $password = '';
    public string $password_confirmation = '';
    public array $profileData = [
        'avatar' => '',
        'id_number' => '',
        'position' => '',
        'class' => '',
        'school_year' => '',
        'address' => '',
        'phone' => '',
        'birth_place' => '',
        'birth_date' => '',
        'gender' => '',
        'blood_type' => '',
        'parent_name' => '',
        'parent_address' => '',
        'parent_phone' => '',
    ];

    public bool $hasSession = false;

    public function mount()
    {
        $this->hasSession = Session::has('email');
    }

    public function registerStepOne()
    {
        $this->validate([
            'accountType' => 'required|in:Student,Teacher',
            'email' => 'required|email|unique:users,email',
        ]);

        // Save email and account type to session
        Session::put('email', $this->email);
        Session::put('account_type', $this->accountType);

        return redirect()->route('register.account');
    }

    public function registerStepTwo()
    {
        $this->registeringUser();
        Session::forget(['account_type', 'email']);

        return redirect()->route('profile');
    }

    public function registeringUser()
    {
        // Validate input data
        $this->validate([
            'accountType' => 'required|in:student,teacher',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'string', 'min:8', 'confirmed', Rules\Password::defaults()],
        ]);

        // Get or create Role
        $role = Role::firstOrCreate(
            [
                'slug' => $this->accountType,
                'name' => $this->accountType,
            ],
        );

        // Create the user
        $user = User::create([
            'email' => $this->email,
            'name' => $this->name,
            'password' => Hash::make($this->password),
        ]);

        // Assign to user role;
        $user->assignRoles([$role]);

        Auth::login($user);
    }
}

<?php

namespace App\Livewire\Forms;

use App\Models\User;
use App\Models\Role;
use App\Models\Profile;

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
            'accountType' => 'required|in:student,teacher',
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

        return redirect()->route('register.profile');
    }

    public function registerStepThree()
    {
        $this->addingUserProfile();

        return redirect()->route('Dashboard');
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

        // Attach role to the user
        $user->roles()->attach($role->id);

        Auth::login($user);
    }

    public function addingUserProfile()
    {
        $this->validate([
            'profileData.id_number' => 'required|string|max:20|unique:profiles,id_number',
            'profileData.avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'profileData.position' => 'nullable|string|max:50',
            'profileData.class' => 'nullable|string|max:50',
            'profileData.school_year' => 'nullable|string|regex:/^\d{4}-\d{4}$/',
            'profileData.address' => 'nullable|string|max:255',
            'profileData.phone' => 'nullable|string|max:15|regex:/^[\d\s\-\+\(\)]+$/',
            'profileData.birth_place' => 'required|string|max:100',
            'profileData.birth_date' => 'required|date|before:today',
            'profileData.gender' => 'required|string|in:male,female',
            'profileData.blood_type' => 'nullable|string|in:A,B,AB,O',
            'profileData.parent_name' => 'nullable|string|max:100',
            'profileData.parent_address' => 'nullable|string|max:255',
            'profileData.parent_phone' => 'nullable|string|max:15|regex:/^[\d\s\-\+\(\)]+$/',
        ]);

        $user = Auth::user();
        Profile::create([
            'user_id' => $user->id,
            'avatar' => $this->profileData['avatar'],
            'id_number' => $this->profileData['id_number'],
            'position' => $this->profileData['position'],
            'class' => $this->profileData['class'],
            'school_year' => $this->profileData['school_year'],
            'address' => $this->profileData['address'],
            'phone' => $this->profileData['phone'],
            'birth_place' => $this->profileData['birth_place'],
            'birth_date' => $this->profileData['birth_date'],
            'gender' => $this->profileData['gender'],
            'blood_type' => $this->profileData['blood_type'],
            'parent_name' => $this->profileData['parent_name'],
            'parent_address' => $this->profileData['parent_address'],
            'parent_phone' => $this->profileData['parent_phone'],
        ]);
    }
}

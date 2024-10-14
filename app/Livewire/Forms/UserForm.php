<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\User;
use App\Models\Status;
use App\Services\RoleService;
use App\Services\UserStatusService;
use Illuminate\Support\Facades\Hash;

class UserForm extends Form
{
    public ?string $userId = null;
    public string $identifier = '';
    public array $user = [];
    public array $userProfile = [];
    public string $userRole = 'guest';
    public string $userStatus = 'user-status-pending';
    public array $roles = [];
    public array $statuses = [];

    public function mount(): void
    {
        $this->loadUserAttributes();
        $this->initUser();
    }

    public function loadUserAttributes(): void
    {
        $this->roles = $this->getRoles();
        $this->statuses = UserStatusService::getStatuses();
    }

    protected function getRoles(): array
    {
        return match ($this->identifier) {
            'admin' => RoleService::getRoles(['admin', 'staff']),
            default => RoleService::getRolesExcluding(['owner', 'admin', 'staff', 'supervisor']),
        };
    }

    public function initUser(): void
    {
        if ($this->userId) {
            $this->loadExistingUser();
        } else {
            $this->initializeNewUser();
        }
    }

    protected function loadExistingUser(): void
    {
        $user = User::with('profile')->findOrFail($this->userId);

        $this->user = [
            'name' => $user->name,
            'email' => $user->email,
            'password' => '',
            'password_confirmation' => '',
        ];

        $this->userProfile = [
            'identifier_number' => $user->profile->identifier_number ?? '',
            'position' => $user->profile->position ?? '',
            'group' => $user->profile->group ?? '',
            'school_year' => $user->profile->school_year ?? '',
            'address' => $user->profile->address ?? '',
            'phone' => $user->profile->phone ?? '',
            'gender' => $user->profile->gender ?? '',
            'parent_name' => $user->profile->parent_name ?? '',
            'parent_address' => $user->profile->parent_address ?? '',
            'parent_phone' => $user->profile->parent_phone ?? '',
        ];

        $this->userRole = $user->getRoleNames()->first() ?? $this->initUserRole();
        $this->userStatus = $user->status->slug ?? $this->initUserStatus();
    }

    protected function initializeNewUser(): void
    {
        $this->user = [
            'name' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
        ];

        $this->userProfile = [
            'identifier_number' => '',
            'position' => '',
            'group' => '',
            'school_year' => '',
            'address' => '',
            'phone' => '',
            'gender' => '',
            'parent_name' => '',
            'parent_address' => '',
            'parent_phone' => '',
        ];

        $this->userRole = $this->initUserRole();
        $this->userStatus = $this->initUserStatus();
    }

    protected function initUserRole(): string
    {
        return match ($this->identifier) {
            'student' => 'student',
            'teacher' => 'teacher',
            'admin' => 'staff',
            default => 'guest',
        };
    }

    protected function initUserStatus(): string
    {
        return match ($this->identifier) {
            'student', 'teacher' => 'user-status-pending',
            'admin' => 'user-status-active',
            default => 'user-status-pending',
        };
    }

    public function saveUser(): void
    {
        $this->validateUser();

        // Set default gender if not provided
        $this->userProfile['gender'] = $this->userProfile['gender'] ?: 'other';

        $this->userId ? $this->updateExistingUser() : $this->createNewUser();
    }

    protected function validateUser(): void
    {
        $rules = $this->getValidationRules();

        $this->validate($rules);
    }

    protected function getValidationRules(): array
    {
        $rules = [
            'user.name' => 'required|string|max:255',
            'user.email' => ['required', 'email', 'max:255', $this->userId ? 'unique:users,email,' . $this->userId : 'unique:users,email'],
            'userRole' => 'required|string',
            'userStatus' => 'required|string',
            'userProfile.identifier_number' => 'nullable|string|min:8|max:20',
            'userProfile.position' => 'nullable|string|max:20',
            'userProfile.address' => 'nullable|min:10|max:255',
            'userProfile.phone' => ['nullable', 'regex:/^0\d{8,12}$/'],
            'userProfile.gender' => 'nullable|in:male,female,other',
        ];

        if (!$this->userId) {
            $rules['user.password'] = 'required|confirmed|min:8';
        } elseif ($this->user['password']) {
            $rules['user.password'] = 'confirmed|min:8';
        }

        return $rules;
    }

    protected function updateExistingUser(): void
    {
        $user = User::findOrFail($this->userId);
        $user->name = $this->user['name'];
        $user->email = $this->user['email'];

        if ($this->user['password']) {
            $user->password = Hash::make($this->user['password']);
        }

        $user->status_id = $this->getStatusId();
        $user->save();

        // Update user profile
        $user->profile()->update($this->userProfile);

        // Update user roles
        $user->syncRoles([$this->userRole]);
    }

    protected function createNewUser(): void
    {
        $user = User::create([
            'name' => $this->user['name'],
            'email' => $this->user['email'],
            'password' => Hash::make($this->user['password']),
            'status_id' => $this->getStatusId(),
        ]);

        // Create user profile
        $user->profile()->create($this->userProfile);

        // Assign user role
        $user->assignRole($this->userRole);
    }

    protected function getStatusId(): ?int
    {
        return Status::where('slug', $this->userStatus)->first()?->id;
    }
}

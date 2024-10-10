<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\User;
use App\Models\UserStatus;
use App\Services\RoleService;
use Livewire\Attributes\Validate;
use App\Services\UserStatusService;
use Illuminate\Support\Facades\Hash;

class UserForm extends Form
{
    public ?string $userId = null;
    public string $identifier = '';

    public array $user = [];
    public array $userProfile = [];
    public string $userRole = 'guest';
    public string $userStatus = 'pending';

    public array $roles = [];
    public array $statuses = [];

    public function loadUserAttributes(): void
    {
        $this->roles = $this->getRoles();
        $this->statuses = UserStatusService::getStatuses();
    }

    public function getRoles()
    {
        return match ($this->identifier) {
            'admin' => RoleService::getRoles(['admin', 'staff']),
            default => RoleService::getRolesExcluding(['owner', 'admin', 'staff']),
        };
    }

    public function initUser(): void
    {
        if ($this->userId) {
            // Mengedit pengguna yang sudah ada
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

            $this->userRole = $user->getRoleNames()->first() ?? 'guest';
            $this->userStatus = $user->status->name ?? 'pending';
        } else {
            // Menambahkan pengguna baru
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
    }

    public function initUserRole()
    {
        return match ($this->identifier) {
            'student' => 'student',
            'teacher' => 'teacher',
            'admin' => 'staff',
            default => 'guest',
        };
    }

    public function initUserStatus()
    {
        return match ($this->identifier) {
            'student' => 'pending',
            'teacher' => 'active',
            'admin' => 'active',
            default => 'guest',
        };
    }

    public function saveUser()
    {
        // Validasi aturan
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

        // Jika menambah pengguna baru, password wajib diisi
        if (!$this->userId) {
            $rules['user.password'] = 'required|confirmed|min:8';
        } else {
            // Jika mengedit, password opsional tetapi harus dikonfirmasi jika diisi
            if ($this->user['password']) {
                $rules['user.password'] = 'confirmed|min:8';
            }
        }

        $this->validate($rules);

        if (empty($this->userProfile['gender'])) {
            $this->userProfile['gender'] = 'other';
        }

        if ($this->userId) {
            // Mengupdate pengguna yang sudah ada
            $user = User::findOrFail($this->userId);
            $user->name = $this->user['name'];
            $user->email = $this->user['email'];

            if ($this->user['password']) {
                $user->password = Hash::make($this->user['password']);
            }

            $user->status_id = UserStatus::where('name', $this->userStatus)->first()->id;
            $user->save();

            // Update profil pengguna
            $user->profile()->update($this->userProfile);

            // Perbarui peran pengguna
            $user->syncRoles([$this->userRole]);
        } else {
            // Membuat pengguna baru
            $user = User::create([
                'name' => $this->user['name'],
                'email' => $this->user['email'],
                'password' => Hash::make($this->user['password']),
                'status_id' => UserStatus::where('name', $this->userStatus)->first()->id,
            ]);

            // Menambahkan profil pengguna
            $user->profile()->create($this->userProfile);

            // Menetapkan peran pengguna
            $user->assignRole($this->userRole);
        }
    }
}

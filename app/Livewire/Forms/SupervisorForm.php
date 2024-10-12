<?php

namespace App\Livewire\Forms;

use Exception;
use Livewire\Form;
use App\Models\User;
use App\Models\AccessKey;
use App\Models\UserStatus;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class SupervisorForm extends Form
{
    public array $errors = [];
    public int $expiryDays = 180;
    public string $roleName = 'supervisor';
    public string $email = '';

    public function createNewSupervisor()
    {
        try {
            $this->validate([
                'email' => 'nullable|email|unique:users,email',
                'expiryDays' => 'required|integer|min:7|max:240',
            ]);

            $user = $this->createNewUser();
            if (!$user) {
                throw new Exception("Gagal membuat pengguna.");
            }

            $accessKey = $this->generateAccessKey($user->id, $this->expiryDays);
            if (!$accessKey) {
                $user->delete();
                throw new Exception("Gagal membuat kunci akses.");
            }

            // Handle success response here if needed
        } catch (Exception $e) {
            // Store error message in the $errors array for view display
            $this->errors[] = $e->getMessage();
        }
    }

    private function generateAccessKey(string $userId = '', int $expiryDays = 180)
    {
        try {
            $keyLength = 16;

            // Generate and store the access key
            $accessKey = AccessKey::createNewKey($keyLength, $userId, $expiryDays);

            if (!$accessKey) {
                throw new Exception("Gagal membuat kunci akses.");
            }

            return $accessKey;
        } catch (Exception $e) {
            $this->errors[] = $e->getMessage();
            return null;
        }
    }

    private function createNewUser()
    {
        try {
            do {
                $name = 'sv-' . Str::random(8);
                $email = $name . '@example.com';
            } while (User::where('email', $email)->exists());

            $password = Hash::make(Str::random(16));
            $status = UserStatus::firstOrCreate(['name' => 'guest']);

            $user = User::create([
                'name' => $name,
                'email' => $this->email ?? $email,
                'password' => $password,
                'status_id' => $status->id,
            ]);

            if (!$user) {
                throw new Exception("Gagal membuat pengguna.");
            }

            $user->assignRole($this->roleName);
            return $user;
        } catch (Exception $e) {
            $this->errors[] = $e->getMessage();
            return null;
        }
    }
}

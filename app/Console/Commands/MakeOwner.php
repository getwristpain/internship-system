<?php

namespace App\Console\Commands;

use App\Helpers\Logger;
use App\Models\Status;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use function Laravel\Prompts\{text, password};

class MakeOwner extends Command
{
    protected $signature = 'make:owner';
    protected $description = 'Create or update the owner user for the application';

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle(): void
    {
        if (!$this->isValidEnvironment()) return;

        try {
            $existingOwner = User::role('owner')->first();
            $existingOwner ? $this->updateOwner($existingOwner) : $this->createOwner();
        } catch (\Throwable $th) {
            $this->handleError('An unexpected error occurred while processing your request.', $th);
        }
    }

    /**
     * Check if the current environment is valid for executing this command.
     *
     * @return bool
     */
    private function isValidEnvironment(): bool
    {
        $validEnvironments = ['local', 'development'];

        if (!in_array(app()->environment(), $validEnvironments)) {
            $this->error(Logger::handle('error', 'This command can only be executed in the local or development environment.'));
            return false;
        }

        return true;
    }

    /**
     * Handle the update of an existing owner user.
     *
     * @param  \App\Models\User  $existingOwner
     * @return void
     */
    private function updateOwner(User $existingOwner): void
    {
        $this->warn('An owner user already exists. This action will update the current owner user\'s data.');

        if ($this->confirm('Do you want to proceed with updating the existing owner user?')) {
            $ownerData = $this->getOwnerData();
            if ($this->validateOwnerData($ownerData, $existingOwner)) {
                $this->performOwnerUpdate($existingOwner, $ownerData);
            }
        } else {
            $this->info('Operation canceled. No changes were made.');
        }
    }

    /**
     * Handle the creation of a new owner user.
     *
     * @return void
     */
    private function createOwner(): void
    {
        $this->info('Creating new owner user...');
        $ownerData = $this->getOwnerData();

        if ($this->validateOwnerData($ownerData)) {
            $this->performUserCreation($ownerData);
        }
    }

    /**
     * Retrieve owner data from user input.
     *
     * @return array
     */
    private function getOwnerData(): array
    {
        try {
            return [
                'name' => text("What is the owner's name?"),
                'email' => text("What is the owner's email?"),
                'password' => password("What is the owner's password?"),
                'password_confirmation' => password("Please confirm the owner's password"),
            ];
        } catch (\Throwable $th) {
            $this->handleError('An error occurred while getting owner data input.', $th);
            return [];
        }
    }

    /**
     * Validate the owner data input.
     *
     * @param  array  $ownerData
     * @param  \App\Models\User|null  $existingOwner
     * @return bool
     */
    private function validateOwnerData(array $ownerData, User $existingOwner = null): bool
    {
        $validator = Validator::make($ownerData, $this->getValidationRules($existingOwner));

        if ($validator->fails()) {
            $this->error($validator->errors()->first());
            exit(1);
            return false;
        }

        return true;
    }

    /**
     * Get the validation rules for owner data.
     *
     * @param  \App\Models\User|null  $existingOwner
     * @return array
     */
    private function getValidationRules(User $existingOwner = null): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required_with:password', 'same:password'],
        ];

        $rules['email'][] = $existingOwner ? 'unique:users,email,' . $existingOwner->id : 'unique:users';

        return $rules;
    }

    /**
     * Create the roles for 'owner' and 'admin' if they don't already exist.
     *
     * @return void
     */
    private function createRoles(): void
    {
        foreach (['owner', 'admin'] as $role) {
            Role::firstOrCreate(['name' => $role]);
            Logger::handle('info', "The role $role has been created or updated.");
        }
    }

    /**
     * Ensure that the 'verified' status exists or create it.
     *
     * @return \App\Models\Status
     */
    private function ensureVerifiedStatus(): Status
    {
        return Status::firstOrCreate(['slug' => 'user-status-verified']);
    }

    /**
     * Perform the user creation process.
     *
     * @param  array  $ownerData
     * @return void
     */
    private function performUserCreation(array $ownerData): void
    {
        try {
            $this->createRoles();
            $status = $this->ensureVerifiedStatus();

            $user = User::create([
                'name' => $ownerData['name'],
                'email' => $ownerData['email'],
                'password' => Hash::make($ownerData['password']),
                'status_id' => $status->id,
            ]);

            $this->syncRolesAndNotify($user);
        } catch (\Throwable $th) {
            $this->handleError('An error occurred while creating the new owner user.', $th);
        }
    }

    /**
     * Perform the owner user update process.
     *
     * @param  \App\Models\User  $existingOwner
     * @param  array  $ownerData
     * @return void
     */
    private function performOwnerUpdate(User $existingOwner, array $ownerData): void
    {
        try {
            $existingOwner->update([
                'name' => $ownerData['name'],
                'email' => $ownerData['email'],
                'password' => Hash::make($ownerData['password']),
            ]);

            $existingOwner->syncRoles(['owner', 'admin']);
            $this->info(Logger::handle('info', 'Owner user updated successfully!'));
        } catch (\Throwable $th) {
            $this->handleError('An error occurred while updating the owner user.', $th);
        }
    }

    /**
     * Sync roles with the user and display appropriate messages.
     *
     * @param  \App\Models\User|null  $user
     * @return void
     */
    private function syncRolesAndNotify(?User $user): void
    {
        if ($user) {
            $user->syncRoles(['owner', 'admin']);
            $this->info(Logger::handle('info', 'Owner user created successfully!'));
        } else {
            $this->error(Logger::handle('error', 'Error creating user.'));
        }
    }

    /**
     * Handle errors and log the exception.
     *
     * @param  string  $message
     * @param  \Throwable  $th
     * @return void
     */
    private function handleError(string $message, \Throwable $th): void
    {
        $this->error(Logger::handle('error', $message, $th));
    }
}

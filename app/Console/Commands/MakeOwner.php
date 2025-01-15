<?php

namespace App\Console\Commands;

use App\Helpers\Exception;
use App\Models\Status;
use App\Models\User;
use function Laravel\Prompts\{text, password};
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class MakeOwner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:owner';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create or update the owner user for the application';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        // Check if the environment is local or development
        if (!in_array(app()->environment(), ['local', 'development'])) {
            $this->error('This command can only be executed in the local or development environment.');
            return;
        }

        try {
            $existingOwner = User::role('owner')->first();

            if ($existingOwner) {
                $this->warn('An owner user already exists. This action will update the current owner user\'s data.');
                $confirm = $this->confirm('Do you want to proceed with updating the existing owner user?');

                if ($confirm) {
                    $this->info('Updating existing owner user...');
                    $ownerData = $this->getOwnerData();
                    if ($this->validateOwnerData($ownerData, $existingOwner)) {
                        $this->updateOwner($existingOwner, $ownerData);
                    }
                } else {
                    $this->info('Operation canceled. No changes were made.');
                }
            } else {
                $this->info('Creating new owner user...');
                $ownerData = $this->getOwnerData();
                if ($this->validateOwnerData($ownerData)) {
                    $this->createUser($ownerData);
                }
            }
        } catch (\Throwable $th) {
            Exception::handle('Error in making owner user.', $th);
            $this->error('An unexpected error occurred while processing your request.');
        }
    }

    /**
     * Get the owner user data from input.
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
            Exception::handle('Error getting owner data input.', $th);
            $this->error('An error occurred while getting owner data input.');
            return [];
        }
    }

    /**
     * Validate the owner user data.
     *
     * @param array $ownerData
     * @param User|null $existingOwner
     * @return bool
     */
    private function validateOwnerData(array $ownerData, User $existingOwner = null): bool
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required_with:password', 'same:password'],
        ];

        // Skip unique email check if we're updating the existing owner
        if ($existingOwner) {
            $rules['email'][] = 'unique:users,email,' . $existingOwner->id;
        } else {
            $rules['email'][] = 'unique:users';
        }

        $validator = Validator::make($ownerData, $rules);

        if ($validator->fails()) {
            $this->error($validator->errors()->first());
            return false;
        }

        return true;
    }

    /**
     * Create a new user based on the given data.
     *
     * @param array $ownerData
     * @return void
     */
    private function createUser(array $ownerData): void
    {
        try {
            $permittedRoles = ['owner', 'admin'];
            foreach ($permittedRoles as $role) {
                Role::firstOrCreate(['name' => $role]);
            }

            $status = Status::firstOrCreate(['slug' => 'user-status-verified']);
            if (!$status) {
                $this->error('User status "Verified" not found.');
                return;
            }

            $user = User::create([
                'name' => $ownerData['name'],
                'email' => $ownerData['email'],
                'password' => Hash::make($ownerData['password']),
                'status_id' => $status->id,
            ]);

            if ($user) {
                $user->syncRoles($permittedRoles);
                $this->info('Owner user created successfully!');
            } else {
                $this->error('Error creating user.');
            }
        } catch (\Throwable $th) {
            Exception::handle('Error creating new owner user.', $th);
            $this->error('An error occurred while creating the new owner user.');
        }
    }

    /**
     * Update the existing owner user with the given data.
     *
     * @param User $existingOwner
     * @param array $ownerData
     * @return void
     */
    private function updateOwner(User $existingOwner, array $ownerData): void
    {
        try {
            $existingOwner->update([
                'name' => $ownerData['name'],
                'email' => $ownerData['email'],
                'password' => Hash::make($ownerData['password']),
            ]);

            $existingOwner->syncRoles(['owner', 'admin']);
            $this->info('Owner user updated successfully!');
        } catch (\Throwable $th) {
            Exception::handle('Error updating existing owner user.', $th);
            $this->error('An error occurred while updating the owner user.');
        }
    }
}

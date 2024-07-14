<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        /**
         * Prompt the user for their name, email, password, and password confirmation.
         */
        $name = $this->ask("What is the user's name?");
        $email = $this->ask("What is the user's email?");
        $password = $this->secret("What is the user's password?");
        $password_confirmation = $this->secret("Please confirm the user's password");

        /**
         * Validate user input and handle the error
         */
        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password_confirmation,
        ], [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            $this->error($validator->errors()->first());
            return;
        }

        /**
         * Get the list of roles and add an option to create a new role
         */
        $roles = Role::all()->pluck('name')->toArray();
        $roles[] = 'Create new role';
        $roleName = $this->choice('Select a role for the user or create a new one', $roles);

        /**
         * Create a new role
         * Perform actions based on the given role name.
         *
         * @param string $roleName
         * @return void
         */
        switch ($roleName) {
            case 'Create new role':
                $roleName = $this->ask('Enter the name of the new role');
                $roleName = Str::studly($roleName);
                $roleSlug = Str::slug($roleName);
                if (Role::where('slug', $roleSlug)->exists()) {
                    $this->error('Role already exists. Please choose a different name.');
                    return;
                }
                $role = Role::create(['name' => $roleName, 'slug' => $roleSlug]);
                $this->info("New role created: {$roleName}");
                break;

            default:
                $role = Role::where('name', $roleName)->first();
                break;
        }

        /**
         * Create a new user record in the database with the provided data.
         *
         * @param array $user An array containing the user's name, email, password, and role ID.
         * @return User The newly created user instance.
         */
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role_id' => $role->id,
        ]);

        /**
         * Associate the role with the user
         */
        $user->role()->associate($role);
        $user->save();

        $this->info("User created successfully with the role: {$roleName}");
    }
}

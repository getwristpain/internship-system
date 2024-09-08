<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use function Laravel\Prompts\{text, password, select};

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
        // Prompt for user input
        $name = text("What is the user's name?");
        $email = text("What is the user's email?");
        $password = password("What is the user's password?");
        $password_confirmation = password("Please confirm the user's password");

        // Validate input
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

        // Get roles and allow new role creation
        $roles = Role::all()->pluck('name')->toArray();
        $roles[] = 'Create new role';
        $roleName = select('Select a role for the user or create a new one', $roles, 'student');

        if ($roleName === 'Create new role') {
            $roleName = text('Enter the name of the new role');
            $roleName = Str::slug($roleName);

            if (Role::where('name', $roleName)->exists()) {
                $this->error('Role already exists. Please choose a different name.');
                return;
            }

            $role = Role::create(['name' => $roleName, 'guard_name' => 'web']);
            $this->info("New role created: {$roleName}");
        } else {
            $role = Role::where('name', $roleName)->first();
        }

        // Create the user
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        // Assign the role to the user
        $user->assignRole($role->name);

        $this->info("User created successfully!");
        $this->info("Name: {$user->name}");
        $this->info("Role: {$role->name}");
    }
}

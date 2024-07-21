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
        $name = $this->ask("What is the user's name?");
        $email = $this->ask("What is the user's email?");
        $password = $this->secret("What is the user's password?");
        $password_confirmation = $this->secret("Please confirm the user's password");

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

        $roles = Role::all()->pluck('name')->toArray();
        $roles[] = 'Create new role';
        $roleName = $this->choice('Select a role for the user or create a new one', $roles);

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

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role_id' => $role->id,
        ]);

        $user->roles()->attach($role->id);

        $this->info("User created successfully with the role: {$roleName}");
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use App\Models\School;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class InstallApp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the application';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting application installation...');

        // Run migrations
        $this->call('migrate:fresh', [
            '--force' => true
        ]);

        // Seed the database
        $this->call('db:seed', [
            '--force' => true
        ]);

        // Other installation steps (e.g., setting up storage links)
        $this->call('storage:link');

        // Create owner (user)
        $this->createOwner();

        // Create school
        $this->createSchool();

        $this->info('Application installed successfully!');
        return 0;
    }

    /**
     * Create an owner user.
     *
     * @return void
     */
    protected function createOwner()
    {
        $this->info('Creating owner user...');

        $name = $this->ask("What is the owner's name?");
        $email = $this->ask("What is the owner's email?");
        $password = $this->secret("What is the owner's password?");
        $password_confirmation = $this->secret("Please confirm the owner's password");

        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password_confirmation,
        ], [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            $this->error($validator->errors()->first());
            return;
        }

        $role = Role::firstOrCreate(['name' => 'Owner', 'slug' => 'owner']);
        $this->info("Role ensured: {$role->name}");

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        if ($user && $role) {
            $user->roles()->attach($role->id);
            $this->info('Owner user created successfully!');
        } else {
            $this->error('Error in creating user or role.');
        }
    }

    /**
     * Create a school.
     *
     * @return void
     */
    protected function createSchool()
    {
        $this->info('Creating school...');

        $name = $this->ask("What is the school's name? (required)");
        $email = $this->ask("What is the school's email? (required)");
        $principal_name = $this->ask("What is the principal's name? (required)");
        $address = $this->ask("What is the school's address?");
        $post_code = $this->ask("What is the school's post code?");
        $telp = $this->ask("What is the school's telephone number?");
        $fax = $this->ask("What is the school's fax number?");
        $contact_person = $this->ask("Who is the contact person?");

        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'principal_name' => $principal_name,
            'address' => $address,
            'post_code' => $post_code,
            'telp' => $telp,
            'fax' => $fax,
            'contact_person' => $contact_person,
        ], [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:schools'],
            'principal_name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'post_code' => ['nullable', 'string', 'max:255'],
            'telp' => ['nullable', 'string', 'max:255'],
            'fax' => ['nullable', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            $this->error($validator->errors()->first());
            return;
        }

        School::create([
            'name' => $name,
            'email' => $email,
            'principal_name' => $principal_name,
            'address' => $address ?: '',
            'post_code' => $post_code ?: '',
            'telp' => $telp ?: '',
            'fax' => $fax ?: '',
            'contact_person' => $contact_person ?: '',
        ]);

        $this->info('School created successfully!');
    }
}

<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\School;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use function Laravel\Prompts\{text, password, confirm};

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

        // Run clearCache
        $this->clearCache();

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

        $this->info('Application installed successfully!');
        return 0;
    }

    /**
     * Clear application caches.
     */
    protected function clearCache()
    {
        $this->info('Clearing all caches...');

        // Clear application cache
        $this->call('cache:clear');
        $this->info('Application cache cleared.');

        // Clear route cache
        $this->call('route:clear');
        $this->info('Route cache cleared.');

        // Clear configuration cache
        $this->call('config:clear');
        $this->info('Configuration cache cleared.');

        // Clear compiled views cache
        $this->call('view:clear');
        $this->info('View cache cleared.');
    }

    /**
     * Create an owner user.
     *
     * @return void
     */
    protected function createOwner()
    {
        $this->info('Creating owner user...');

        // Prompt user for input using Laravel Prompts
        $name = text("What is the owner's name?");
        $email = text("What is the owner's email?");
        $password = password("What is the owner's password?");
        $password_confirmation = password("Please confirm the owner's password");

        // Validate user input
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

        // Create user
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        if ($user) {
            // Assign roles
            $user->syncRoles(['admin', 'owner']);
            $this->info('Owner user created successfully!');
        } else {
            $this->error('Error in creating user.');
        }
    }
}

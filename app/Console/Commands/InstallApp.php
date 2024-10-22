<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Status;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use function Laravel\Prompts\{text, password};

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
    public function handle(): int
    {
        $this->info('Starting application installation...');

        $this->clearCache();
        $this->runMigrations();
        $this->seedDatabase();
        $this->cleanStorage();
        $this->linkStorage();

        if ($this->createOwner()) {
            $this->info('Application installed successfully!');
            return 0;
        }

        $this->error('Application installation failed!');
        return 1;
    }

    /**
     * Clear application caches.
     */
    protected function clearCache(): void
    {
        $this->info('Clearing all caches...');
        $this->call('optimize:clear');
    }

    /**
     * Run database migrations.
     */
    protected function runMigrations(): void
    {
        $this->info('Running migrations...');
        $this->call('migrate:fresh', ['--force' => true]);
    }

    /**
     * Seed the database.
     */
    protected function seedDatabase(): void
    {
        $this->info('Seeding the database...');
        $this->call('db:seed', ['--force' => true]);
    }

    /**
     * Delete temporary files and all files in storage/app/public/uploads
     */
    protected function cleanStorage()
    {
        File::cleanDirectory(storage_path('logs'));
        Storage::deleteDirectory('livewire-tmp');
        Storage::disk('public')->deleteDirectory('uploads');

        $this->info('Temporary files have been deleted.');
    }

    protected function linkStorage()
    {
        if (!File::exists(public_path('storage'))) {
            $this->call('storage:link');
            $this->info('Storage linked successfully.');
        } else {
            $this->info('Public storage directory already exists.');
        }
    }

    /**
     * Create an owner user.
     *
     * @return bool
     */
    protected function createOwner(): bool
    {
        $this->info('Creating owner user...');

        $name = text("What is the owner's name?");
        $email = text("What is the owner's email?");
        $password = password("What is the owner's password?");
        $passwordConfirmation = password("Please confirm the owner's password");

        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $passwordConfirmation,
        ], [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required_with:password', 'same:password'],
        ]);

        if ($validator->fails()) {
            $this->error($validator->errors()->first());
            return false;
        }

        $status = Status::firstOrCreate(['slug' => 'user-status-verified']);

        if (!$status) {
            $this->error('User status "Verified" not found.');
            return false;
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'status_id' => $status->id,
        ]);

        if ($user) {
            $user->syncRoles(['owner', 'admin']);
            $this->info('Owner user created successfully!');
            return true;
        }

        $this->error('Error creating user.');
        return false;
    }
}

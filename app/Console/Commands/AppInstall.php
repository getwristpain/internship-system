<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Status;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use function Laravel\Prompts\{text, password};

class AppInstall extends Command
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
     * Store error messages during the installation process.
     *
     * @var array
     */
    protected $errors = [];

    /**
     * Handle the application installation process.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->info('Starting application installation...');
        try {
            $this->performInstallationSteps();

            if (!empty($this->errors)) {
                $this->error('Application installation failed!');
                foreach ($this->errors as $error) {
                    $error = htmlspecialchars($error, ENT_QUOTES, 'UTF-8');
                    $this->error("[x] $error");
                }
            } else {
                $this->info('Application installed successfully!');
            }
        } catch (\Throwable $exception) {
            $this->handleError($exception);
        }
    }

    /**
     * Handle errors that occur during the application installation process.
     *
     * @param \Throwable $exception The exception that occurred.
     * @return void
     */
    private function handleError(\Throwable $exception): void
    {
        Log::error('Application installation failed', [
            'message' => $exception->getMessage(),
            'stack' => $exception->getTraceAsString(),
        ]);

        $this->error('Application installation failed! Please check the log for more details.');
    }

    /**
     * Perform all necessary steps for the application installation.
     *
     * @return void
     * @throws \Throwable If any installation step fails.
     */
    private function performInstallationSteps(): void
    {
        $this->clearCache();
        $this->runMigrations();
        $this->seedDatabase();
        $this->cleanStorage();
        $this->linkStorage();
        $this->createOwner();
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
    protected function cleanStorage(): void
    {
        $this->deleteTemporaryFiles();
        $this->info('Temporary files have been deleted.');
    }

    /**
     * Delete temporary files and specific folders.
     */
    protected function deleteTemporaryFiles(): void
    {
        File::cleanDirectory(storage_path('logs'));
        Storage::deleteDirectory('livewire-tmp');
        Storage::disk('public')->deleteDirectory('uploads');
    }

    /**
     * Link storage to the public directory.
     */
    protected function linkStorage(): void
    {
        if ($this->isStorageLinked()) {
            $this->info('Public storage directory already exists.');
            return;
        }

        $this->call('storage:link');
        $this->info('Storage linked successfully.');
    }

    /**
     * Check if the storage is already linked.
     *
     * @return bool
     */
    protected function isStorageLinked(): bool
    {
        return File::exists(public_path('storage'));
    }

    /**
     * Create the owner user.
     *
     * @return bool
     */
    protected function createOwner(): bool
    {
        $this->info('Creating owner user...');

        $ownerData = $this->getOwnerData();

        if ($this->validateOwnerData($ownerData)) {
            return $this->createUser($ownerData);
        }

        return false;
    }

    /**
     * Get the owner user data from input.
     *
     * @return array
     */
    protected function getOwnerData(): array
    {
        return [
            'name' => text("What is the owner's name?"),
            'email' => text("What is the owner's email?"),
            'password' => password("What is the owner's password?"),
            'password_confirmation' => password("Please confirm the owner's password"),
        ];
    }

    /**
     * Validate the owner user data.
     *
     * @param array $ownerData
     * @return bool
     */
    protected function validateOwnerData(array $ownerData): bool
    {
        $previousLocale = app()->getLocale();
        app()->setLocale('en');

        $validator = Validator::make($ownerData, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required_with:password', 'same:password'],
        ]);

        if ($validator->fails()) {
            $this->errors[] = $validator->errors()->first();
            app()->setLocale($previousLocale);
            return false;
        }

        app()->setLocale($previousLocale);
        return true;
    }

    /**
     * Create a new user based on the given data.
     *
     * @param array $ownerData
     * @return bool
     */
    protected function createUser(array $ownerData): bool
    {
        $status = Status::firstOrCreate(['slug' => 'user-status-verified']);

        if (!$status) {
            $this->errors[] = 'User status "Verified" not found.';
            return false;
        }

        $user = User::create([
            'name' => $ownerData['name'],
            'email' => $ownerData['email'],
            'password' => Hash::make($ownerData['password']),
            'status_id' => $status->id,
        ]);

        if ($user) {
            $user->syncRoles(['owner', 'admin']);
            $this->info('Owner user created successfully!');
            return true;
        }

        $this->errors[] = 'Error creating user.';
        return false;
    }
}

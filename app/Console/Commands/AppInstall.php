<?php

namespace App\Console\Commands;

use App\Helpers\Logger;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class AppInstall extends Command
{
    protected $signature = 'app:install';
    protected $description = 'Install the application';
    protected $errors = [];

    /**
     * Handle the application installation process.
     */
    public function handle(): void
    {
        $this->info('Starting application installation...');

        try {
            $this->installApp();
            $this->errors ? $this->handleInstallationFailure() : $this->info(Logger::handle('info', 'Application installed successfully!'));
        } catch (\Throwable $th) {
            $this->handleUnexpectedError($th);
        }
    }

    /**
     * Perform the entire installation process.
     */
    protected function installApp(): void
    {
        $this->clearCache();
        $this->runMigrations();
        $this->cleanStorage();
        $this->linkStorage();
        $this->seedDatabase();
        $this->createOwner();
        $this->runAppBuild();
    }

    /**
     * Handle installation failure.
     */
    protected function handleInstallationFailure(): void
    {
        $this->error(Logger::handle('error', 'Application installation failed!'));
        foreach ($this->errors as $error) {
            $this->error("[x] " . htmlspecialchars($error, ENT_QUOTES, 'UTF-8'));
        }
        exit(1);
    }

    /**
     * Handle an unexpected error during installation.
     */
    protected function handleUnexpectedError(\Throwable $th): void
    {
        Logger::handle('error', 'An unexpected error occurred during installation', $th);
        $this->error('An unexpected error occurred: ' . $th->getMessage());
        exit(1);
    }

    /**
     * Clear the application cache.
     */
    protected function clearCache(): void
    {
        $this->executeCommand('optimize:clear', 'Clearing all caches...', 'Cache cleared successfully.');
    }

    /**
     * Seed the application database.
     */
    protected function seedDatabase(): void
    {
        $this->executeCommand('db:seed', 'Seeding the database...', 'Database seeding completed successfully.');
    }

    /**
     * Clean the application storage by removing temporary and unwanted files.
     */
    protected function cleanStorage(): void
    {
        try {
            $this->info('Cleaning up storage...');
            File::cleanDirectory(storage_path('logs'));
            Storage::deleteDirectory('livewire-tmp');
            Storage::disk('public')->deleteDirectory('uploads');
            $this->info('Storage cleaned successfully.');
        } catch (\Throwable $th) {
            $this->handleError('Failed to clean storage', $th);
        }
    }

    /**
     * Link the public storage directory.
     */
    protected function linkStorage(): void
    {
        try {
            $this->info('Linking storage...');
            if (!$this->isStorageLinked()) {
                $this->call('storage:link');
                $this->info('Storage linked successfully.');
            } else {
                $this->info('Public storage directory already exists.');
            }
        } catch (\Throwable $th) {
            $this->handleError('Failed to link storage', $th);
        }
    }

    /**
     * Check if the storage is already linked.
     */
    protected function isStorageLinked(): bool
    {
        return File::exists(public_path('storage'));
    }

    /**
     * Run the application migrations.
     */
    protected function runMigrations(): void
    {
        try {
            $this->info('Running migrations...');
            $this->call('migrate');
            $this->call('migrate:fresh');
            $this->info('Migrations completed successfully.');
        } catch (\Throwable $th) {
            $this->handleError('Migration failed', $th);
        }
    }

    /**
     * Create the owner user if in development or local environment.
     */
    protected function createOwner(): void
    {
        if (app()->environment(['development', 'local'])) {
            $this->executeCommand('make:owner', 'Creating owner user...', 'Owner user creation completed.');
        } else {
            $this->info('Owner user creation is skipped in the current environment.');
        }
    }

    /**
     * Run the npm build process.
     */
    protected function runAppBuild(): void
    {
        try {
            $this->info('Running npm build...');
            $output = [];
            $resultCode = null;
            exec('npm run build', $output, $resultCode);

            if ($resultCode !== 0) {
                $this->errors[] = 'Failed to build the app. Please check the output for errors.';
                $this->error('npm run build failed!');
                $this->error(implode("\n", $output));
                exit(1);
            }
            $this->info('App build completed successfully!');
        } catch (\Throwable $th) {
            $this->handleError('Failed to run npm build', $th);
        }
    }

    /**
     * Execute an Artisan command with error handling.
     */
    protected function executeCommand(string $command, string $startMessage, string $successMessage): void
    {
        try {
            $this->info($startMessage);
            $this->call($command);
            $this->info($successMessage);
        } catch (\Throwable $th) {
            $this->handleError('Command execution failed', $th);
        }
    }

    /**
     * Handle errors by logging them and displaying the message to the user.
     */
    protected function handleError(string $message, \Throwable $th): void
    {
        Logger::handle('error', $message, $th);
        $this->error("$message: " . $th->getMessage());
        exit(1);
    }
}

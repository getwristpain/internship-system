<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Helpers\Exception;

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
                    $this->error("[x] " . htmlspecialchars($error, ENT_QUOTES, 'UTF-8'));
                }
            } else {
                $this->info('Application installed successfully!');
            }
        } catch (\Throwable $exception) {
            $this->errorHandler($exception, 'Application installation failed');
        }
    }

    /**
     * Perform all necessary steps for the application installation.
     *
     * @return void
     * @throws \Throwable If any installation step fails.
     */
    private function performInstallationSteps(): void
    {
        $steps = [
            'clearCache' => 'Clearing all caches...',
            'runMigrations' => 'Running migrations...',
            'seedDatabase' => 'Seeding the database...',
            'cleanStorage' => 'Deleting temporary files...',
            'linkStorage' => 'Linking storage...',
            'createOwner' => 'Creating owner user...',
            'runAppBuild' => 'Running npm build...'
        ];

        foreach ($steps as $method => $message) {
            $this->info($message);
            try {
                $this->$method();
            } catch (\Throwable $exception) {
                $this->errorHandler($exception, $message);
            }
        }
    }

    /**
     * Centralized error handling.
     *
     * @param \Throwable $exception
     * @param string $context
     * @return void
     */
    private function errorHandler(\Throwable $exception, string $context): void
    {
        // Log the error for internal tracking
        Exception::handle("Error during $context", $exception);

        // Add the error to the errors array to be shown in the console
        $this->errors[] = "$context failed: " . $exception->getMessage();

        // Output the error message in the console
        $this->error("$context failed!");
    }

    /**
     * Clear application caches.
     */
    private function clearCache(): void
    {
        $this->call('optimize:clear');
    }

    /**
     * Run database migrations.
     */
    private function runMigrations(): void
    {
        $this->call('migrate');
        $this->call('migrate:fresh', ['--force' => true]);
    }

    /**
     * Seed the database.
     */
    private function seedDatabase(): void
    {
        $this->call('db:seed', ['--force' => true]);
    }

    /**
     * Delete temporary files and all files in storage/app/public/uploads
     */
    private function cleanStorage(): void
    {
        File::cleanDirectory(storage_path('logs'));
        Storage::deleteDirectory('livewire-tmp');
        Storage::disk('public')->deleteDirectory('uploads');
    }

    /**
     * Link storage to the public directory.
     */
    private function linkStorage(): void
    {
        if (!$this->isStorageLinked()) {
            $this->call('storage:link');
            $this->info('Storage linked successfully.');
        } else {
            $this->info('Public storage directory already exists.');
        }
    }

    /**
     * Check if the storage is already linked.
     *
     * @return bool
     */
    private function isStorageLinked(): bool
    {
        return File::exists(public_path('storage'));
    }

    /**
     * Create the owner user.
     */
    private function createOwner(): void
    {
        if (in_array(app()->environment(), ['local', 'development'])) {
            $this->call('make:owner');
        } else {
            $this->info('Owner user creation is skipped in the current environment.');
        }
    }

    /**
     * Run the app build process.
     */
    private function runAppBuild(): void
    {
        try {
            $output = [];
            $resultCode = null;

            exec('npm run build', $output, $resultCode);

            if ($resultCode !== 0) {
                $this->errors[] = 'Failed to build the app. Please check the output for errors.';
                $this->error('npm run build failed!');
                $this->error(implode("\n", $output));
            } else {
                $this->info('App build completed successfully!');
            }
        } catch (\Throwable $exception) {
            $this->errorHandler($exception, 'Build process');
        }
    }
}

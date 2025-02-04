<?php

namespace App\Console\Commands;

use App\Helpers\Logger;
use App\Services\UserService;
use Illuminate\Console\Command;
use function Laravel\Prompts\{text, password};

class MakeOwner extends Command
{
    protected $signature = 'make:owner';
    protected $description = 'Create or update the owner user for the application';

    protected UserService $userService;

    /**
     * MakeOwner constructor.
     *
     * @param  \App\Services\UserService  $userService
     * @return void
     */
    public function __construct(UserService $userService)
    {
        parent::__construct();
        $this->userService = $userService;
    }

    /**
     * Execute the console command to create or update the owner user.
     *
     * @return void
     */
    public function handle(): void
    {
        if (!$this->isValidEnvironment()) return;

        try {
            $ownerData = $this->getOwnerData();
            $existingOwner = $this->userService->getOwner();

            if ($this->userService->createOrUpdateOwner($ownerData, $existingOwner)) {
                $this->info('Owner user has been successfully created!');
            } else {
                $this->error('An error occurred during the owner creation.');
            }
        } catch (\Throwable $th) {
            $this->handleError('An unexpected error occurred while processing your request.', $th);
        }
    }

    /**
     * Retrieve owner data from user input.
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
            $this->handleError('An error occurred while getting owner data input.', $th);
            return [];
        }
    }

    /**
     * Handle errors and log the exception.
     *
     * @param  string  $message
     * @param  \Throwable  $th
     * @return void
     */
    private function handleError(string $message, \Throwable $th): void
    {
        $this->error(Logger::handle('error', $message, $th));
    }

    /**
     * Check if the current environment is valid for executing this command.
     *
     * @return bool
     */
    private function isValidEnvironment(): bool
    {
        $validEnvironments = ['local', 'development'];
        if (!in_array(app()->environment(), $validEnvironments)) {
            $this->error(Logger::handle('error', 'This command can only be executed in the local or development environment.'));
            return false;
        }
        return true;
    }
}

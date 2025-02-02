<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeHelper extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:helper {className}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new helper class by calling make:logic command with extends from App\\Helpers\\Helper';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $className = $this->argument('className');

        // Call the make:logic command with the specified parameters
        $this->call('make:logic', [
            'name' => 'Helpers/' . $className,
            '--extends' => 'App\\Helpers\\Helper'
        ]);

        $this->info("Helper class '$className' has been created successfully!");
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {className}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new service class by calling make:logic command with extends from App\\Services\\Service';

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
            'name' => 'Services/' . $className,
            '--extends' => 'App\\Services\\Service'
        ]);

        $this->info("Service class '$className' has been created successfully!");
    }
}

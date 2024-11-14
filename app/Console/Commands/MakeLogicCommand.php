<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeLogicCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:logic {name} {--path=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new feature logic class in the specified path in /app directory';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $pathOption = $this->option('path');

        // Define default path if no path option is provided
        $path = $pathOption ? base_path($pathOption) : app_path();

        // Ensure the directory exists
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        // Build the namespace based on the path (inside 'app')
        $namespace = $this->buildNamespace($path);

        // Build the full class path
        $filePath = $path . '/' . $name . '.php';

        // Class template
        $classTemplate = <<<CLASS
<?php

namespace $namespace;

class $name
{
    //
}
CLASS;

        // Check if the file already exists
        if (File::exists($filePath)) {
            $this->error("Class already exists at $filePath");
            return;
        }

        // Create the class file
        File::put($filePath, $classTemplate);

        $this->info("Class created at $filePath");
    }

    /**
     * Build the namespace based on the path inside 'app/' folder.
     *
     * @param string \$path
     * @return string
     */
    protected function buildNamespace($path)
    {
        // Remove the base app path and normalize slashes
        $relativePath = str_replace(base_path() . '/', '', $path);
        $relativePath = str_replace('/', '\\', $relativePath);

        // Build namespace from the relative path
        $namespace = 'App' . ($relativePath ? '\\' . trim($relativePath, '\\') : '');

        return $namespace;
    }
}

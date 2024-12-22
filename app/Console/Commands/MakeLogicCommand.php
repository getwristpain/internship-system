<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeLogicCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:logic {name : The folder/class name in format Folder/ClassName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new utility class with a dynamic namespace inside app directory';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');

        // Split the name argument into folder and class parts
        $nameParts = explode('/', $name);
        $className = Str::studly(array_pop($nameParts)); // Get the last part as class name
        $subPath = implode('/', $nameParts); // Remaining parts form the path

        // Define the full path under app/
        $fullPath = app_path($subPath);

        // Create the directory if it doesn't exist
        if (!File::exists($fullPath)) {
            File::makeDirectory($fullPath, 0755, true);
        }

        // Generate the namespace
        $namespace = $this->buildNamespace($subPath);

        // Build the file path
        $filePath = $fullPath . '/' . $className . '.php';

        // Class template
        $classTemplate = <<<CLASS
<?php

namespace $namespace;

class $className
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
     * Build the namespace based on the sub-path inside 'app/' folder.
     *
     * @param string \$subPath
     * @return string
     */
    protected function buildNamespace($subPath)
    {
        // Normalize slashes and build the namespace
        $namespace = 'App' . ($subPath ? '\\' . str_replace('/', '\\', trim($subPath, '/')) : '');

        return $namespace;
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeLang extends Command
{
    // Command signature
    protected $signature = 'make:lang {language} {filename}';

    // Command description
    protected $description = 'Generate a language file for the specified language and filename';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $language = $this->argument('language');
        $filename = $this->argument('filename');

        $path = base_path("lang/{$language}");

        // Ensure the language folder exists
        $this->ensureDirectoryExists($path);

        $filePath = "{$path}/{$filename}.php";

        // Check if the file already exists
        if (File::exists($filePath)) {
            $this->error("File {$filename}.php already exists.");
            return;
        }

        // Create the file with default content
        $this->createLanguageFile($filePath);

        $this->info("Language file {$filename}.php created successfully.");
    }

    /**
     * Ensure that the directory exists, create it if it doesn't.
     *
     * @param string $path
     * @return void
     */
    private function ensureDirectoryExists(string $path): void
    {
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }
    }

    /**
     * Create the language file with basic content.
     *
     * @param string $filePath
     * @return void
     */
    private function createLanguageFile(string $filePath): void
    {
        $content = "<?php\n\nreturn [\n    // Add your translations here\n];\n";
        File::put($filePath, $content);
    }
}

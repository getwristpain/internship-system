<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Helpers\Logger;

class MakeLogic extends Command
{
    protected $signature = 'make:logic {name : The folder/class name in format Folder/ClassName}
                            {--extends= : The class to extend from (e.g., App\\Helpers\\Formatter, Illuminate\\Console\\Command)}';

    protected $description = 'Create a new utility class with a dynamic namespace inside app directory';

    protected $reservedClassNames = [
        'Array',
        'array',
        'bool',
        'Class',
        'double',
        'Error',
        'Exception',
        'false',
        'float',
        'Function',
        'int',
        'null',
        'Number',
        'Object',
        'object',
        'stdClass',
        'String',
        'string',
        'Throwable',
        'true',
    ];

    public function handle(): void
    {
        $name = $this->argument('name');
        $extends = $this->option('extends');

        [$namespace, $filePath, $className] = $this->getClassDetails($name);

        if ($this->isReservedClassName($className)) {
            $message = Logger::handle('error', "The class name '$className' is reserved and cannot be used.");
            $this->error($message);
            return;
        }

        $extends = $this->validateExtendsClass($extends);

        if ($this->classExists($filePath)) {
            return;
        }

        $classContent = $this->generateClassContent($namespace, $className, $extends);
        File::put($filePath, $classContent);

        $this->info(Logger::handle('info', "Class $className created successfully at $filePath"));
    }

    protected function getClassDetails(string $name): array
    {
        $nameParts = explode('/', $name);
        $className = Str::studly(array_pop($nameParts));
        $subPath = implode('/', $nameParts);

        $fullPath = app_path($subPath);
        $this->createDirectoryIfNeeded($fullPath);

        $namespace = $this->buildNamespace($subPath);
        $filePath = $fullPath . '/' . $className . '.php';

        return [$namespace, $filePath, $className];
    }

    protected function buildNamespace(string $subPath): string
    {
        return 'App' . ($subPath ? '\\' . str_replace('/', '\\', trim($subPath, '/')) : '');
    }

    protected function createDirectoryIfNeeded(string $fullPath): void
    {
        if (!File::exists($fullPath)) {
            File::makeDirectory($fullPath, 0755, true);
        }
    }

    protected function classExists(string $filePath): bool
    {
        if (File::exists($filePath)) {
            $message = Logger::handle('error', "Class already exists at $filePath");
            $this->error($message);
            return true;
        }
        return false;
    }

    protected function validateExtendsClass(?string $extends): ?string
    {
        if (!$extends) {
            return null;
        }

        if (class_exists($extends)) {
            return $extends;
        }

        $message = Logger::handle('error', "The class $extends does not exist.");
        $this->error($message);
        exit;
    }

    /**
     * Validate if the class name is in the reserved list
     *
     * @param string $className
     * @return bool
     */
    protected function isReservedClassName(string $className): bool
    {
        return in_array($className, $this->reservedClassNames);
    }

    /**
     * Get the short class name without namespace.
     *
     * @param string $class
     * @return string
     */
    protected function generateClassContent(string $namespace, string $className, ?string $extends): string
    {
        $template = File::get(resource_path('templates/__class_template.stub'));
        $extendsStatement = $extends ? "extends " . class_basename($extends) : '';

        $useStatement = $extends ? "\nuse $extends;\n" : '';
        $classStatement = $extends ? $className . ' ' . $extendsStatement : $className;

        return str_replace(
            ['{{ namespace }}', '{{ useStatement }}', '{{ classStatement }}'],
            [$namespace ?? '', $useStatement ?? '', $classStatement ?? ''],
            $template
        );
    }
}

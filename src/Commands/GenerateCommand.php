<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateCommand extends Command
{
    protected $signature = 'sapb1:generate
                            {name : The name of the entity (e.g., ProductionOrder)}
                            {--module=Custom : The module name (e.g., Sales, Purchase, Inventory)}
                            {--entity= : The SAP B1 entity name (defaults to pluralized name)}
                            {--type=all : What to generate (all, dto, builder, action)}
                            {--force : Overwrite existing files}';

    protected $description = 'Generate SAP B1 Toolkit files (DTO, Builder, Action)';

    public function handle(): int
    {
        $name = $this->argument('name');
        $module = $this->option('module');
        $entity = $this->option('entity') ?: Str::plural($name);
        $type = $this->option('type');
        $force = $this->option('force');

        if (! is_string($name) || ! is_string($module) || ! is_string($entity) || ! is_string($type)) {
            $this->error('Invalid arguments provided.');

            return self::FAILURE;
        }

        $this->info("Generating SAP B1 Toolkit files for: {$name}");
        $this->newLine();

        $generated = [];

        if ($type === 'all' || $type === 'dto') {
            if ($this->generateDto($name, $module, $force)) {
                $generated[] = 'DTO';
            }
        }

        if ($type === 'all' || $type === 'builder') {
            if ($this->generateBuilder($name, $module, $force)) {
                $generated[] = 'Builder';
            }
        }

        if ($type === 'all' || $type === 'action') {
            if ($this->generateAction($name, $module, $entity, $force)) {
                $generated[] = 'Action';
            }
        }

        $this->newLine();

        if (empty($generated)) {
            $this->warn('No files were generated. Use --force to overwrite existing files.');

            return self::SUCCESS;
        }

        $this->info('Generated: '.implode(', ', $generated));

        return self::SUCCESS;
    }

    private function generateDto(string $name, string $module, bool $force): bool
    {
        $path = $this->getDtoPath($name, $module);

        if (file_exists($path) && ! $force) {
            $this->warn("DTO already exists: {$path}");

            return false;
        }

        $stub = $this->getStub('dto');
        $content = $this->replacePlaceholders($stub, [
            '{{ namespace }}' => "SapB1\\Toolkit\\DTOs\\{$module}",
            '{{ class }}' => $name,
        ]);

        $this->ensureDirectoryExists(dirname($path));
        file_put_contents($path, $content);

        $this->components->task("Creating DTO: {$name}Dto", fn () => true);

        return true;
    }

    private function generateBuilder(string $name, string $module, bool $force): bool
    {
        $path = $this->getBuilderPath($name, $module);

        if (file_exists($path) && ! $force) {
            $this->warn("Builder already exists: {$path}");

            return false;
        }

        $stub = $this->getStub('builder');
        $content = $this->replacePlaceholders($stub, [
            '{{ namespace }}' => "SapB1\\Toolkit\\Builders\\{$module}",
            '{{ class }}' => $name,
        ]);

        $this->ensureDirectoryExists(dirname($path));
        file_put_contents($path, $content);

        $this->components->task("Creating Builder: {$name}Builder", fn () => true);

        return true;
    }

    private function generateAction(string $name, string $module, string $entity, bool $force): bool
    {
        $path = $this->getActionPath($name, $module);

        if (file_exists($path) && ! $force) {
            $this->warn("Action already exists: {$path}");

            return false;
        }

        $stub = $this->getStub('action');
        $content = $this->replacePlaceholders($stub, [
            '{{ namespace }}' => "SapB1\\Toolkit\\Actions\\{$module}",
            '{{ dtoNamespace }}' => "SapB1\\Toolkit\\DTOs\\{$module}",
            '{{ builderNamespace }}' => "SapB1\\Toolkit\\Builders\\{$module}",
            '{{ class }}' => $name,
            '{{ entity }}' => $entity,
        ]);

        $this->ensureDirectoryExists(dirname($path));
        file_put_contents($path, $content);

        $this->components->task("Creating Action: {$name}Action", fn () => true);

        return true;
    }

    private function getDtoPath(string $name, string $module): string
    {
        return $this->getBasePath()."/src/DTOs/{$module}/{$name}Dto.php";
    }

    private function getBuilderPath(string $name, string $module): string
    {
        return $this->getBasePath()."/src/Builders/{$module}/{$name}Builder.php";
    }

    private function getActionPath(string $name, string $module): string
    {
        return $this->getBasePath()."/src/Actions/{$module}/{$name}Action.php";
    }

    private function getBasePath(): string
    {
        return dirname(__DIR__, 2);
    }

    private function getStub(string $type): string
    {
        $stubPath = $this->getBasePath()."/stubs/{$type}.stub";

        if (! file_exists($stubPath)) {
            throw new \RuntimeException("Stub file not found: {$stubPath}");
        }

        $content = file_get_contents($stubPath);

        if ($content === false) {
            throw new \RuntimeException("Failed to read stub file: {$stubPath}");
        }

        return $content;
    }

    /**
     * @param  array<string, string>  $replacements
     */
    private function replacePlaceholders(string $stub, array $replacements): string
    {
        return str_replace(
            array_keys($replacements),
            array_values($replacements),
            $stub
        );
    }

    private function ensureDirectoryExists(string $path): void
    {
        if (! is_dir($path)) {
            mkdir($path, 0755, true);
        }
    }
}

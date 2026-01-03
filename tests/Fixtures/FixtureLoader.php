<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Tests\Fixtures;

use RuntimeException;

/**
 * Helper class to load test fixtures.
 */
final class FixtureLoader
{
    private static ?string $basePath = null;

    /**
     * Load a fixture file and return its contents as an array.
     *
     * @param  string  $name  Fixture name without extension (e.g., 'orders', 'invoices')
     * @param  string|null  $key  Optional key to return a specific part of the fixture
     * @return array<string, mixed>
     *
     * @throws RuntimeException If fixture file not found
     */
    public static function load(string $name, ?string $key = null): array
    {
        $path = self::getBasePath().'/'.$name.'.json';

        if (! file_exists($path)) {
            throw new RuntimeException("Fixture file not found: {$path}");
        }

        $content = file_get_contents($path);

        if ($content === false) {
            throw new RuntimeException("Failed to read fixture file: {$path}");
        }

        /** @var array<string, mixed> $data */
        $data = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException('Failed to parse fixture JSON: '.json_last_error_msg());
        }

        if ($key !== null) {
            if (! isset($data[$key])) {
                throw new RuntimeException("Key '{$key}' not found in fixture '{$name}'");
            }

            /** @var array<string, mixed> */
            return $data[$key];
        }

        return $data;
    }

    /**
     * Load a single entity fixture.
     *
     * @return array<string, mixed>
     */
    public static function single(string $name): array
    {
        return self::load($name, 'single');
    }

    /**
     * Load a list fixture.
     *
     * @return array<string, mixed>
     */
    public static function list(string $name): array
    {
        return self::load($name, 'list');
    }

    /**
     * Load the list values only (without metadata like nextLink).
     *
     * @return array<int, array<string, mixed>>
     */
    public static function listValues(string $name): array
    {
        $list = self::load($name, 'list');

        /** @var array<int, array<string, mixed>> */
        return $list['value'] ?? [];
    }

    /**
     * Load a created entity fixture.
     *
     * @return array<string, mixed>
     */
    public static function created(string $name): array
    {
        return self::load($name, 'created');
    }

    /**
     * Load an error fixture.
     *
     * @return array<string, mixed>
     */
    public static function error(string $name): array
    {
        return self::load($name, 'error');
    }

    /**
     * Get the base path for fixtures.
     */
    private static function getBasePath(): string
    {
        if (self::$basePath === null) {
            self::$basePath = __DIR__;
        }

        return self::$basePath;
    }

    /**
     * Set a custom base path for fixtures.
     */
    public static function setBasePath(string $path): void
    {
        self::$basePath = $path;
    }

    /**
     * Reset the base path to default.
     */
    public static function resetBasePath(): void
    {
        self::$basePath = null;
    }

    /**
     * Get available fixture names.
     *
     * @return array<int, string>
     */
    public static function available(): array
    {
        $files = glob(self::getBasePath().'/*.json');

        if ($files === false) {
            return [];
        }

        return array_map(
            fn (string $file) => basename($file, '.json'),
            $files
        );
    }
}

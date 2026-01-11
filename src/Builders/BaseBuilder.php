<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders;

use SapB1\Toolkit\Contracts\BuilderInterface;

/**
 * Base builder class for SAP B1 data structures.
 *
 * Provides common functionality for all builders including data storage,
 * fluent setters, and array conversion. All concrete builder classes
 * should extend this class or DocumentBuilder.
 *
 * @phpstan-consistent-constructor
 */
abstract class BaseBuilder implements BuilderInterface
{
    /**
     * The builder data storage.
     *
     * @var array<string, mixed>
     */
    protected array $data = [];

    /**
     * Create a new builder instance.
     */
    public static function create(): static
    {
        return new static;
    }

    /**
     * Reset the builder to its initial state.
     */
    public function reset(): static
    {
        $this->data = [];

        return $this;
    }

    /**
     * Set a value in the builder data.
     *
     * @param  string  $key  The data key
     * @param  mixed  $value  The value to set
     */
    protected function set(string $key, mixed $value): static
    {
        $this->data[$key] = $value;

        return $this;
    }

    /**
     * Get a value from the builder data.
     *
     * @param  string  $key  The data key
     * @param  mixed  $default  Default value if key doesn't exist
     */
    protected function get(string $key, mixed $default = null): mixed
    {
        return $this->data[$key] ?? $default;
    }

    /**
     * Check if a key exists in the builder data.
     *
     * @param  string  $key  The data key
     */
    protected function has(string $key): bool
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * Convert the builder to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->build();
    }

    /**
     * Build and return the final data array.
     *
     * @return array<string, mixed>
     */
    abstract public function build(): array;

    // ==================== UDF SUPPORT ====================

    /**
     * Set a User Defined Field (UDF) value.
     *
     * UDF names can be provided with or without the U_ prefix.
     *
     * @param  string  $name  The UDF name (with or without U_ prefix)
     * @param  mixed  $value  The value to set
     *
     * @example
     * ```php
     * $builder->udf('CustomField', 'value');     // Sets U_CustomField
     * $builder->udf('U_CustomField', 'value');   // Also sets U_CustomField
     * ```
     */
    public function udf(string $name, mixed $value): static
    {
        $key = $this->normalizeUdfKey($name);

        return $this->set($key, $value);
    }

    /**
     * Set multiple UDF values at once.
     *
     * @param  array<string, mixed>  $udfs  Array of UDF name => value pairs
     *
     * @example
     * ```php
     * $builder->udfs([
     *     'CustomField' => 'value',
     *     'AnotherField' => 123,
     * ]);
     * ```
     */
    public function udfs(array $udfs): static
    {
        foreach ($udfs as $name => $value) {
            $this->udf($name, $value);
        }

        return $this;
    }

    /**
     * Get a UDF value from the builder data.
     *
     * @param  string  $name  The UDF name (with or without U_ prefix)
     * @param  mixed  $default  Default value if not set
     */
    public function getUdf(string $name, mixed $default = null): mixed
    {
        $key = $this->normalizeUdfKey($name);

        return $this->get($key, $default);
    }

    /**
     * Check if a UDF is set in the builder data.
     *
     * @param  string  $name  The UDF name (with or without U_ prefix)
     */
    public function hasUdf(string $name): bool
    {
        $key = $this->normalizeUdfKey($name);

        return $this->has($key);
    }

    /**
     * Normalize UDF key to include U_ prefix if not present.
     */
    protected function normalizeUdfKey(string $name): string
    {
        if (str_starts_with($name, 'U_')) {
            return $name;
        }

        return 'U_' . $name;
    }
}

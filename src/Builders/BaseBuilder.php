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
}

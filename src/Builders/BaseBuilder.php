<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders;

use SapB1\Toolkit\Contracts\BuilderInterface;

/**
 * @phpstan-consistent-constructor
 */
abstract class BaseBuilder implements BuilderInterface
{
    /**
     * @var array<string, mixed>
     */
    protected array $data = [];

    public static function create(): static
    {
        return new static;
    }

    public function reset(): static
    {
        $this->data = [];

        return $this;
    }

    protected function set(string $key, mixed $value): static
    {
        $this->data[$key] = $value;

        return $this;
    }

    protected function get(string $key, mixed $default = null): mixed
    {
        return $this->data[$key] ?? $default;
    }

    protected function has(string $key): bool
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->build();
    }

    /**
     * @return array<string, mixed>
     */
    abstract public function build(): array;
}

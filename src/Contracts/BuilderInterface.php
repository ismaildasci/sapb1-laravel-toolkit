<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Contracts;

interface BuilderInterface
{
    /**
     * Build and return the data array.
     *
     * @return array<string, mixed>
     */
    public function build(): array;

    /**
     * Reset the builder state.
     */
    public function reset(): static;

    /**
     * Convert to array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array;
}

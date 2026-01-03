<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Contracts;

/**
 * Contract for SAP B1 builder classes.
 *
 * Builders provide a fluent interface for constructing complex
 * data structures to be sent to the SAP B1 Service Layer API.
 */
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

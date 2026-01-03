<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Contracts;

interface DtoInterface
{
    /**
     * Create instance from array.
     *
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): static;

    /**
     * Create instance from SAP B1 API response.
     *
     * @param  array<string, mixed>  $response
     */
    public static function fromResponse(array $response): static;

    /**
     * Convert to array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array;

    /**
     * Convert to JSON string.
     */
    public function toJson(): string;
}

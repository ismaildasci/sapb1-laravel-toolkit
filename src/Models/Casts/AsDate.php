<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Models\Casts;

use DateTimeImmutable;
use SapB1\Toolkit\Models\SapB1Model;

/**
 * Cast attribute to date.
 */
class AsDate implements CastInterface
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function get(SapB1Model $model, string $key, mixed $value, array $attributes): ?DateTimeImmutable
    {
        if ($value === null || $value === '') {
            return null;
        }

        if ($value instanceof DateTimeImmutable) {
            return $value;
        }

        if ($value instanceof \DateTime) {
            return DateTimeImmutable::createFromMutable($value);
        }

        // SAP B1 date format: 2024-01-15
        $date = DateTimeImmutable::createFromFormat('Y-m-d', (string) $value);

        if ($date === false) {
            // Try with time component
            $date = DateTimeImmutable::createFromFormat('Y-m-d\TH:i:s', (string) $value);
        }

        return $date ?: null;
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function set(SapB1Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d');
        }

        if (is_string($value)) {
            return $value;
        }

        return null;
    }
}

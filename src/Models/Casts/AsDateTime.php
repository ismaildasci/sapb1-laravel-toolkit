<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Models\Casts;

use DateTimeImmutable;
use SapB1\Toolkit\Models\SapB1Model;

/**
 * Cast attribute to datetime.
 */
class AsDateTime implements CastInterface
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

        // Try various SAP B1 datetime formats
        $formats = [
            'Y-m-d\TH:i:s',
            'Y-m-d\TH:i:sP',
            'Y-m-d H:i:s',
            'Y-m-d',
        ];

        foreach ($formats as $format) {
            $date = DateTimeImmutable::createFromFormat($format, (string) $value);
            if ($date !== false) {
                return $date;
            }
        }

        return null;
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
            return $value->format('Y-m-d\TH:i:s');
        }

        if (is_string($value)) {
            return $value;
        }

        return null;
    }
}

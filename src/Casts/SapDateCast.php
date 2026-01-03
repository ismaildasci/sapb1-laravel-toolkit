<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Casts;

use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * @implements CastsAttributes<Carbon|null, string|null>
 */
class SapDateCast implements CastsAttributes
{
    /**
     * Cast the given value from SAP B1 date format.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?Carbon
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_string($value)) {
            // SAP B1 returns dates in format like "2024-01-15" or "20240115"
            if (strlen($value) === 8 && ctype_digit($value)) {
                return Carbon::createFromFormat('Ymd', $value);
            }

            return Carbon::parse($value);
        }

        return null;
    }

    /**
     * Prepare the given value for storage in SAP B1 date format.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d');
        }

        if (is_string($value)) {
            return Carbon::parse($value)->format('Y-m-d');
        }

        return null;
    }
}

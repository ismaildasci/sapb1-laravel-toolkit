<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * @implements CastsAttributes<bool, string>
 */
class SapBooleanCast implements CastsAttributes
{
    /**
     * Cast the given value from SAP B1 boolean format (tYES/tNO).
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        if (is_string($value)) {
            return strtolower($value) === 'tyes' || $value === 'Y' || $value === '1';
        }

        if (is_numeric($value)) {
            return (bool) $value;
        }

        return false;
    }

    /**
     * Prepare the given value for storage in SAP B1 boolean format.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): string
    {
        return $value ? 'tYES' : 'tNO';
    }
}

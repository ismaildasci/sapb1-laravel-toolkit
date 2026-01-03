<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Models\Casts;

use SapB1\Toolkit\Models\SapB1Model;

/**
 * Cast attribute to boolean.
 */
class AsBoolean implements CastInterface
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function get(SapB1Model $model, string $key, mixed $value, array $attributes): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        if (is_string($value)) {
            $lower = strtolower($value);

            return in_array($lower, ['true', 'yes', 'y', '1', 'tyes'], true);
        }

        return (bool) $value;
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function set(SapB1Model $model, string $key, mixed $value, array $attributes): string
    {
        // SAP B1 uses 'tYES' and 'tNO' for some boolean fields
        return $value ? 'tYES' : 'tNO';
    }
}

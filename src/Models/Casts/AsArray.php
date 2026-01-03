<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Models\Casts;

use SapB1\Toolkit\Models\SapB1Model;

/**
 * Cast attribute to array.
 */
class AsArray implements CastInterface
{
    /**
     * @param  array<string, mixed>  $attributes
     * @return array<mixed>
     */
    public function get(SapB1Model $model, string $key, mixed $value, array $attributes): array
    {
        if (is_array($value)) {
            return $value;
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);

            return is_array($decoded) ? $decoded : [];
        }

        return [];
    }

    /**
     * @param  array<string, mixed>  $attributes
     * @return array<mixed>|string
     */
    public function set(SapB1Model $model, string $key, mixed $value, array $attributes): array|string
    {
        if (is_array($value)) {
            return $value;
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);

            return is_array($decoded) ? $decoded : [];
        }

        return [];
    }
}

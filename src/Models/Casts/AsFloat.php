<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Models\Casts;

use SapB1\Toolkit\Models\SapB1Model;

/**
 * Cast attribute to float.
 */
class AsFloat implements CastInterface
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function get(SapB1Model $model, string $key, mixed $value, array $attributes): float
    {
        return (float) $value;
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function set(SapB1Model $model, string $key, mixed $value, array $attributes): float
    {
        return (float) $value;
    }
}

<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Models\Casts;

use SapB1\Toolkit\Models\SapB1Model;

/**
 * Cast attribute to decimal with precision.
 */
class AsDecimal implements CastInterface
{
    protected int $precision;

    public function __construct(int $precision = 2)
    {
        $this->precision = $precision;
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function get(SapB1Model $model, string $key, mixed $value, array $attributes): float
    {
        if ($value === null) {
            return 0.0;
        }

        $floatValue = (float) $value;

        return round($floatValue, $this->precision);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function set(SapB1Model $model, string $key, mixed $value, array $attributes): float
    {
        if ($value === null) {
            return 0.0;
        }

        $floatValue = (float) $value;

        return round($floatValue, $this->precision);
    }
}

<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * @implements CastsAttributes<float, float|string>
 */
class MoneyAmountCast implements CastsAttributes
{
    public function __construct(
        private readonly int $precision = 2
    ) {}

    /**
     * Cast the given value to a properly formatted monetary amount.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): float
    {
        if ($value === null) {
            return 0.0;
        }

        return round((float) $value, $this->precision);
    }

    /**
     * Prepare the given value for storage with proper precision.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): float|string
    {
        if ($value === null) {
            return 0.0;
        }

        return round((float) $value, $this->precision);
    }
}

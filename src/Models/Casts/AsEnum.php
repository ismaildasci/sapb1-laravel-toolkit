<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Models\Casts;

use BackedEnum;
use SapB1\Toolkit\Models\SapB1Model;

/**
 * Cast attribute to enum.
 *
 * @template T of BackedEnum
 */
class AsEnum implements CastInterface
{
    /**
     * @param  class-string<T>  $enumClass
     */
    public function __construct(
        protected string $enumClass
    ) {}

    /**
     * @param  array<string, mixed>  $attributes
     * @return T|null
     */
    public function get(SapB1Model $model, string $key, mixed $value, array $attributes): ?BackedEnum
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof $this->enumClass) {
            return $value;
        }

        return $this->enumClass::tryFrom($value);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function set(SapB1Model $model, string $key, mixed $value, array $attributes): int|string|null
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof BackedEnum) {
            return $value->value;
        }

        return $value;
    }
}

<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use SapB1\Toolkit\Enums\CardType;

/**
 * @implements CastsAttributes<CardType|null, string|null>
 */
class CardTypeCast implements CastsAttributes
{
    /**
     * Cast the given value to a CardType enum.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?CardType
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof CardType) {
            return $value;
        }

        return CardType::tryFrom((string) $value);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if ($value === null) {
            return null;
        }

        // Handle CardType enum
        if (is_object($value) && $value instanceof CardType) {
            return $value->value;
        }

        return (string) $value;
    }
}

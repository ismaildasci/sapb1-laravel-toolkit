<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use SapB1\Toolkit\Enums\DocumentType;

/**
 * @implements CastsAttributes<DocumentType|null, int|null>
 */
class DocumentTypeCast implements CastsAttributes
{
    /**
     * Cast the given value to a DocumentType enum.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?DocumentType
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof DocumentType) {
            return $value;
        }

        return DocumentType::tryFrom((int) $value);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): ?int
    {
        if ($value === null) {
            return null;
        }

        // Handle DocumentType enum
        if (is_object($value) && $value instanceof DocumentType) {
            return $value->value;
        }

        return (int) $value;
    }
}

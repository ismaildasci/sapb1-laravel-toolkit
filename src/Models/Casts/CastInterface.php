<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Models\Casts;

use SapB1\Toolkit\Models\SapB1Model;

/**
 * Interface for custom attribute casts.
 */
interface CastInterface
{
    /**
     * Transform the attribute from the SAP B1 value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(SapB1Model $model, string $key, mixed $value, array $attributes): mixed;

    /**
     * Transform the attribute to SAP B1 format for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(SapB1Model $model, string $key, mixed $value, array $attributes): mixed;
}

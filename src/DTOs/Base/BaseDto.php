<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Base;

use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;
use SapB1\Toolkit\Contracts\DtoInterface;

/**
 * @implements Arrayable<string, mixed>
 *
 * @phpstan-consistent-constructor
 */
abstract class BaseDto implements Arrayable, DtoInterface, JsonSerializable
{
    public static function fromArray(array $data): static
    {
        return new static(...static::mapFromArray($data));
    }

    public static function fromResponse(array $response): static
    {
        return static::fromArray($response);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    abstract protected static function mapFromArray(array $data): array;

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $properties = get_object_vars($this);

        return array_filter($properties, fn ($value) => $value !== null);
    }

    public function toJson(): string
    {
        return (string) json_encode($this->toArray(), JSON_THROW_ON_ERROR);
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}

<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Models\Concerns;

use BackedEnum;
use DateTimeImmutable;
use SapB1\Toolkit\Models\Casts\AsArray;
use SapB1\Toolkit\Models\Casts\AsBoolean;
use SapB1\Toolkit\Models\Casts\AsDate;
use SapB1\Toolkit\Models\Casts\AsDateTime;
use SapB1\Toolkit\Models\Casts\AsFloat;
use SapB1\Toolkit\Models\Casts\AsInteger;
use SapB1\Toolkit\Models\Casts\CastInterface;

/**
 * Handles automatic type casting for attributes.
 */
trait HasCasting
{
    /**
     * Built-in cast types.
     *
     * @var array<string, class-string<CastInterface>>
     */
    protected static array $builtInCasts = [
        'boolean' => AsBoolean::class,
        'bool' => AsBoolean::class,
        'integer' => AsInteger::class,
        'int' => AsInteger::class,
        'float' => AsFloat::class,
        'double' => AsFloat::class,
        'array' => AsArray::class,
        'date' => AsDate::class,
        'datetime' => AsDateTime::class,
    ];

    /**
     * Cached caster instances.
     *
     * @var array<string, CastInterface>
     */
    protected array $casterCache = [];

    /**
     * Get the casts array for this model.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [];
    }

    /**
     * Cast an attribute to its PHP type.
     */
    protected function castAttribute(string $key, mixed $value): mixed
    {
        if ($value === null) {
            return null;
        }

        $casts = $this->casts();

        if (! isset($casts[$key])) {
            return $value;
        }

        $castType = $casts[$key];

        // Handle decimal:precision format
        if (str_starts_with($castType, 'decimal:')) {
            $precision = (int) substr($castType, 8);

            return $this->castDecimal($value, $precision);
        }

        // Handle enum casting
        if (is_subclass_of($castType, BackedEnum::class)) {
            return $this->castEnum($value, $castType);
        }

        // Handle custom cast class
        if (class_exists($castType) && is_subclass_of($castType, CastInterface::class)) {
            return $this->getCaster($castType)->get($this, $key, $value, $this->attributes);
        }

        // Handle built-in cast types
        if (isset(self::$builtInCasts[$castType])) {
            $casterClass = self::$builtInCasts[$castType];

            return $this->getCaster($casterClass)->get($this, $key, $value, $this->attributes);
        }

        return $value;
    }

    /**
     * Cast value to decimal with precision.
     */
    protected function castDecimal(mixed $value, int $precision): float
    {
        if (is_string($value)) {
            $value = (float) $value;
        }

        return round((float) $value, $precision);
    }

    /**
     * Cast value to enum.
     *
     * @param  class-string<BackedEnum>  $enumClass
     */
    protected function castEnum(mixed $value, string $enumClass): ?BackedEnum
    {
        if ($value instanceof $enumClass) {
            return $value;
        }

        return $enumClass::tryFrom($value);
    }

    /**
     * Get or create a caster instance.
     *
     * @param  class-string<CastInterface>  $casterClass
     */
    protected function getCaster(string $casterClass): CastInterface
    {
        if (! isset($this->casterCache[$casterClass])) {
            $this->casterCache[$casterClass] = new $casterClass;
        }

        return $this->casterCache[$casterClass];
    }

    /**
     * Prepare attribute for SAP storage.
     */
    protected function prepareForSap(string $key, mixed $value): mixed
    {
        if ($value === null) {
            return null;
        }

        $casts = $this->casts();

        if (! isset($casts[$key])) {
            return $value;
        }

        $castType = $casts[$key];

        // Handle enum
        if ($value instanceof BackedEnum) {
            return $value->value;
        }

        // Handle date/datetime
        if ($value instanceof DateTimeImmutable) {
            return $value->format('Y-m-d');
        }

        if ($value instanceof \DateTime) {
            return $value->format('Y-m-d');
        }

        // Handle custom cast class
        if (class_exists($castType) && is_subclass_of($castType, CastInterface::class)) {
            return $this->getCaster($castType)->set($this, $key, $value, $this->attributes);
        }

        return $value;
    }

    /**
     * Check if attribute has a cast.
     */
    public function hasCast(string $key, ?string $type = null): bool
    {
        $casts = $this->casts();

        if (! isset($casts[$key])) {
            return false;
        }

        if ($type === null) {
            return true;
        }

        return $casts[$key] === $type;
    }

    /**
     * Get cast type for an attribute.
     */
    public function getCastType(string $key): ?string
    {
        $casts = $this->casts();

        return $casts[$key] ?? null;
    }
}

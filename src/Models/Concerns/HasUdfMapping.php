<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Models\Concerns;

use Carbon\Carbon;
use DateTimeInterface;
use InvalidArgumentException;

/**
 * Trait for mapping User Defined Fields (UDFs) to friendly property names.
 *
 * Extends the HasUdf trait to provide property-style access to UDFs
 * with type casting and validation support.
 *
 * @example
 * ```php
 * class Order extends SapB1Model
 * {
 *     use HasUdf, HasUdfMapping;
 *
 *     // Define UDF mappings with optional types
 *     protected array $udfMappings = [
 *         'deliveryDate' => ['field' => 'U_DeliveryDate', 'type' => 'date'],
 *         'customerId' => ['field' => 'U_CustomerID', 'type' => 'integer'],
 *         'priority' => 'U_Priority', // Simple mapping without type
 *         'isUrgent' => ['field' => 'U_Urgent', 'type' => 'boolean'],
 *         'metadata' => ['field' => 'U_Metadata', 'type' => 'json'],
 *     ];
 *
 *     // Now access UDFs as properties:
 *     $order->deliveryDate; // Returns Carbon instance
 *     $order->isUrgent; // Returns boolean
 *     $order->priority = 'high'; // Sets U_Priority
 * }
 * ```
 */
trait HasUdfMapping
{
    /**
     * Cache for resolved UDF mappings.
     *
     * @var array<string, array{field: string, type: string|null}>|null
     */
    private ?array $resolvedMappings = null;

    /**
     * Boot the trait.
     */
    public static function bootHasUdfMapping(): void
    {
        // Validate mappings on first use
    }

    /**
     * Get the UDF mappings defined on this model.
     *
     * @return array<string, string|array{field: string, type?: string}>
     */
    public function getUdfMappings(): array
    {
        if (property_exists($this, 'udfMappings')) {
            return $this->udfMappings;
        }

        return [];
    }

    /**
     * Get resolved mappings with normalized structure.
     *
     * @return array<string, array{field: string, type: string|null}>
     */
    protected function resolveUdfMappings(): array
    {
        if ($this->resolvedMappings !== null) {
            return $this->resolvedMappings;
        }

        $this->resolvedMappings = [];

        foreach ($this->getUdfMappings() as $alias => $mapping) {
            if (is_string($mapping)) {
                $this->resolvedMappings[$alias] = [
                    'field' => $mapping,
                    'type' => null,
                ];
            } else {
                $this->resolvedMappings[$alias] = [
                    'field' => $mapping['field'],
                    'type' => $mapping['type'] ?? null,
                ];
            }
        }

        return $this->resolvedMappings;
    }

    /**
     * Check if an alias is a mapped UDF.
     */
    public function isMappedUdf(string $alias): bool
    {
        return array_key_exists($alias, $this->resolveUdfMappings());
    }

    /**
     * Get the UDF field name for an alias.
     */
    public function getMappedUdfField(string $alias): ?string
    {
        $mappings = $this->resolveUdfMappings();

        return $mappings[$alias]['field'] ?? null;
    }

    /**
     * Get the type for a mapped UDF.
     */
    public function getMappedUdfType(string $alias): ?string
    {
        $mappings = $this->resolveUdfMappings();

        return $mappings[$alias]['type'] ?? null;
    }

    /**
     * Get a mapped UDF value with type casting.
     */
    public function getMappedUdf(string $alias): mixed
    {
        $mappings = $this->resolveUdfMappings();

        if (! isset($mappings[$alias])) {
            return null;
        }

        $field = $mappings[$alias]['field'];
        $type = $mappings[$alias]['type'];
        $value = $this->getUdf($field);

        if ($value === null) {
            return null;
        }

        return $this->castUdfValue($value, $type);
    }

    /**
     * Set a mapped UDF value with type handling.
     */
    public function setMappedUdf(string $alias, mixed $value): static
    {
        $mappings = $this->resolveUdfMappings();

        if (! isset($mappings[$alias])) {
            throw new InvalidArgumentException("Unknown UDF mapping: {$alias}");
        }

        $field = $mappings[$alias]['field'];
        $type = $mappings[$alias]['type'];

        $storedValue = $this->prepareUdfValue($value, $type);

        return $this->setUdf($field, $storedValue);
    }

    /**
     * Get all mapped UDF values.
     *
     * @return array<string, mixed>
     */
    public function getMappedUdfs(): array
    {
        $result = [];

        foreach ($this->resolveUdfMappings() as $alias => $mapping) {
            $result[$alias] = $this->getMappedUdf($alias);
        }

        return $result;
    }

    /**
     * Set multiple mapped UDF values.
     *
     * @param  array<string, mixed>  $values
     */
    public function setMappedUdfs(array $values): static
    {
        foreach ($values as $alias => $value) {
            if ($this->isMappedUdf($alias)) {
                $this->setMappedUdf($alias, $value);
            }
        }

        return $this;
    }

    /**
     * Fill model with data, handling mapped UDFs.
     *
     * @param  array<string, mixed>  $data
     */
    public function fillMapped(array $data): static
    {
        foreach ($data as $key => $value) {
            if ($this->isMappedUdf($key)) {
                $this->setMappedUdf($key, $value);
            }
        }

        return $this;
    }

    /**
     * Convert a retrieved UDF value to the appropriate type.
     */
    protected function castUdfValue(mixed $value, ?string $type): mixed
    {
        if ($type === null || $value === null) {
            return $value;
        }

        return match ($type) {
            'integer', 'int' => (int) $value,
            'float', 'double', 'decimal' => (float) $value,
            'boolean', 'bool' => $this->castToBoolean($value),
            'string' => (string) $value,
            'date' => $this->castToDate($value),
            'datetime' => $this->castToDateTime($value),
            'json', 'array' => $this->castToArray($value),
            default => $value,
        };
    }

    /**
     * Prepare a value for storage in a UDF.
     */
    protected function prepareUdfValue(mixed $value, ?string $type): mixed
    {
        if ($type === null || $value === null) {
            return $value;
        }

        return match ($type) {
            'integer', 'int' => (int) $value,
            'float', 'double', 'decimal' => (float) $value,
            'boolean', 'bool' => $value ? 'Y' : 'N',
            'string' => (string) $value,
            'date' => $this->prepareDate($value),
            'datetime' => $this->prepareDateTime($value),
            'json', 'array' => is_string($value) ? $value : json_encode($value),
            default => $value,
        };
    }

    /**
     * Cast a value to a boolean.
     *
     * Handles SAP B1's Y/N convention as well as standard boolean values.
     */
    protected function castToBoolean(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        if (is_string($value)) {
            $value = strtoupper(trim($value));

            // SAP B1 uses Y/N for boolean fields
            if ($value === 'Y' || $value === 'YES' || $value === '1' || $value === 'TRUE') {
                return true;
            }

            if ($value === 'N' || $value === 'NO' || $value === '0' || $value === 'FALSE' || $value === '') {
                return false;
            }
        }

        return (bool) $value;
    }

    /**
     * Cast a value to a date (Carbon instance).
     */
    protected function castToDate(mixed $value): ?Carbon
    {
        if ($value instanceof DateTimeInterface) {
            return Carbon::instance($value)->startOfDay();
        }

        if (is_string($value)) {
            return Carbon::parse($value)->startOfDay();
        }

        return null;
    }

    /**
     * Cast a value to a datetime (Carbon instance).
     */
    protected function castToDateTime(mixed $value): ?Carbon
    {
        if ($value instanceof DateTimeInterface) {
            return Carbon::instance($value);
        }

        if (is_string($value)) {
            return Carbon::parse($value);
        }

        return null;
    }

    /**
     * Cast a value to an array.
     *
     * @return array<mixed>
     */
    protected function castToArray(mixed $value): array
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
     * Prepare a date for storage.
     */
    protected function prepareDate(mixed $value): ?string
    {
        if ($value instanceof DateTimeInterface) {
            return $value->format('Y-m-d');
        }

        if (is_string($value)) {
            return Carbon::parse($value)->format('Y-m-d');
        }

        return null;
    }

    /**
     * Prepare a datetime for storage.
     */
    protected function prepareDateTime(mixed $value): ?string
    {
        if ($value instanceof DateTimeInterface) {
            return $value->format('Y-m-d H:i:s');
        }

        if (is_string($value)) {
            return Carbon::parse($value)->format('Y-m-d H:i:s');
        }

        return null;
    }

    /**
     * Get all UDF mapping aliases.
     *
     * @return array<int, string>
     */
    public function getUdfAliases(): array
    {
        return array_keys($this->resolveUdfMappings());
    }

    /**
     * Get the reverse mapping (field -> alias).
     *
     * @return array<string, string>
     */
    public function getReverseUdfMappings(): array
    {
        $reverse = [];

        foreach ($this->resolveUdfMappings() as $alias => $mapping) {
            $reverse[$mapping['field']] = $alias;
        }

        return $reverse;
    }

    /**
     * Convert the model to array with mapped UDF aliases.
     *
     * @return array<string, mixed>
     */
    public function toArrayWithMappedUdfs(): array
    {
        $attributes = $this->getAttributes();
        $reverse = $this->getReverseUdfMappings();

        foreach ($reverse as $field => $alias) {
            // Normalize field key
            $normalizedField = $this->normalizeUdfKey(str_replace('U_', '', $field));

            if (array_key_exists($normalizedField, $attributes)) {
                $type = $this->getMappedUdfType($alias);
                $attributes[$alias] = $this->castUdfValue($attributes[$normalizedField], $type);
            }
        }

        return $attributes;
    }

    /**
     * Magic getter to support mapped UDFs as properties.
     *
     * Override this in your model's __get method:
     * ```php
     * public function __get($key)
     * {
     *     if ($this->isMappedUdf($key)) {
     *         return $this->getMappedUdf($key);
     *     }
     *     return parent::__get($key);
     * }
     * ```
     */
    // Note: Magic methods are typically defined in the base model class
    // The trait provides the helper methods to use in __get/__set

    /**
     * Get a UDF value (required by trait contract).
     * Should be provided by HasUdf trait.
     *
     * @param  string  $name  The UDF name
     */
    abstract public function getUdf(string $name): mixed;

    /**
     * Set a UDF value (required by trait contract).
     * Should be provided by HasUdf trait.
     *
     * @param  string  $name  The UDF name
     * @param  mixed  $value  The value to set
     */
    abstract public function setUdf(string $name, mixed $value): static;

    /**
     * Normalize UDF key (required by trait contract).
     * Should be provided by HasUdf trait.
     */
    abstract protected function normalizeUdfKey(string $name): string;

    /**
     * Get all attributes.
     * Should be provided by the model class.
     *
     * @return array<string, mixed>
     */
    abstract public function getAttributes(): array;
}

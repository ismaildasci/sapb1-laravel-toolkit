<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Models\Concerns;

/**
 * Trait for models that support User Defined Fields (UDFs).
 *
 * Provides convenience methods for accessing UDF values on SAP B1 entities.
 * UDFs are stored with a U_ prefix (e.g., U_CustomField) and this trait
 * provides cleaner access methods.
 *
 * @example
 * ```php
 * $order = Order::find(123);
 *
 * // Get UDF value (returns U_CustomField)
 * $value = $order->getUdf('CustomField');
 *
 * // Set UDF value
 * $order->setUdf('CustomField', 'new value');
 * $order->save();
 *
 * // Get all UDFs
 * $udfs = $order->getUdfs();
 * // ['CustomField' => 'value', 'AnotherField' => 123]
 *
 * // Check if UDF exists
 * if ($order->hasUdf('CustomField')) { ... }
 *
 * // Bulk set UDFs
 * $order->setUdfs([
 *     'CustomField' => 'value',
 *     'AnotherField' => 123,
 * ]);
 * ```
 */
trait HasUdf
{
    /**
     * UDF attribute prefix.
     */
    protected static string $udfPrefix = 'U_';

    /**
     * Get a UDF value.
     *
     * @param  string  $name  The UDF name (without U_ prefix)
     * @return mixed The UDF value or null if not set
     */
    public function getUdf(string $name): mixed
    {
        $key = $this->normalizeUdfKey($name);

        return $this->getAttribute($key);
    }

    /**
     * Set a UDF value.
     *
     * @param  string  $name  The UDF name (without U_ prefix)
     * @param  mixed  $value  The value to set
     */
    public function setUdf(string $name, mixed $value): static
    {
        $key = $this->normalizeUdfKey($name);
        $this->setAttribute($key, $value);

        return $this;
    }

    /**
     * Check if a UDF exists in the model's attributes.
     *
     * @param  string  $name  The UDF name (without U_ prefix)
     */
    public function hasUdf(string $name): bool
    {
        $key = $this->normalizeUdfKey($name);

        return $this->hasAttribute($key);
    }

    /**
     * Get all UDF values.
     *
     * Returns an array of UDF values with the U_ prefix stripped from keys.
     *
     * @return array<string, mixed>
     */
    public function getUdfs(): array
    {
        $udfs = [];
        $prefix = static::$udfPrefix;
        $prefixLength = strlen($prefix);

        foreach ($this->getAttributes() as $key => $value) {
            if (str_starts_with($key, $prefix)) {
                $udfName = substr($key, $prefixLength);
                $udfs[$udfName] = $value;
            }
        }

        return $udfs;
    }

    /**
     * Get all UDF values with original keys (including U_ prefix).
     *
     * @return array<string, mixed>
     */
    public function getUdfsRaw(): array
    {
        $udfs = [];
        $prefix = static::$udfPrefix;

        foreach ($this->getAttributes() as $key => $value) {
            if (str_starts_with($key, $prefix)) {
                $udfs[$key] = $value;
            }
        }

        return $udfs;
    }

    /**
     * Set multiple UDF values at once.
     *
     * @param  array<string, mixed>  $udfs  Array of UDF name => value pairs
     */
    public function setUdfs(array $udfs): static
    {
        foreach ($udfs as $name => $value) {
            $this->setUdf($name, $value);
        }

        return $this;
    }

    /**
     * Get the count of UDFs on this model.
     */
    public function udfCount(): int
    {
        return count($this->getUdfs());
    }

    /**
     * Check if this model has any UDFs set.
     */
    public function hasAnyUdfs(): bool
    {
        return $this->udfCount() > 0;
    }

    /**
     * Get UDF names that are set on this model.
     *
     * @return array<int, string>
     */
    public function getUdfNames(): array
    {
        return array_keys($this->getUdfs());
    }

    /**
     * Remove a UDF value (set to null).
     *
     * @param  string  $name  The UDF name (without U_ prefix)
     */
    public function clearUdf(string $name): static
    {
        return $this->setUdf($name, null);
    }

    /**
     * Fill UDFs from an array, only setting values for UDFs that exist in the array.
     *
     * @param  array<string, mixed>  $data  Data array that may contain UDF keys
     * @param  array<int, string>  $udfNames  List of UDF names to look for
     */
    public function fillUdfs(array $data, array $udfNames): static
    {
        foreach ($udfNames as $name) {
            $key = $this->normalizeUdfKey($name);

            if (array_key_exists($key, $data)) {
                $this->setUdf($name, $data[$key]);
            } elseif (array_key_exists($name, $data)) {
                $this->setUdf($name, $data[$name]);
            }
        }

        return $this;
    }

    /**
     * Get only the dirty (changed) UDFs.
     *
     * @return array<string, mixed>
     */
    public function getDirtyUdfs(): array
    {
        $dirtyUdfs = [];
        $prefix = static::$udfPrefix;
        $prefixLength = strlen($prefix);

        foreach ($this->getDirty() as $key => $value) {
            if (str_starts_with($key, $prefix)) {
                $udfName = substr($key, $prefixLength);
                $dirtyUdfs[$udfName] = $value;
            }
        }

        return $dirtyUdfs;
    }

    /**
     * Check if a specific UDF has been changed.
     *
     * @param  string  $name  The UDF name (without U_ prefix)
     */
    public function isUdfDirty(string $name): bool
    {
        $key = $this->normalizeUdfKey($name);

        return $this->isDirty($key);
    }

    /**
     * Normalize UDF key to include prefix if not present.
     *
     * @param  string  $name  The UDF name (with or without U_ prefix)
     */
    protected function normalizeUdfKey(string $name): string
    {
        $prefix = static::$udfPrefix;

        if (str_starts_with($name, $prefix)) {
            return $name;
        }

        return $prefix.$name;
    }

    /**
     * Get an attribute value.
     * Should be provided by the model class.
     *
     * @return mixed
     */
    abstract public function getAttribute(string $key);

    /**
     * Set an attribute value.
     * Should be provided by the model class.
     *
     * @return mixed
     */
    abstract public function setAttribute(string $key, mixed $value);

    /**
     * Check if an attribute exists.
     * Should be provided by the model class.
     */
    abstract public function hasAttribute(string $key): bool;

    /**
     * Get all attributes.
     * Should be provided by the model class.
     *
     * @return array<string, mixed>
     */
    abstract public function getAttributes(): array;

    /**
     * Get dirty attributes.
     * Should be provided by the model class.
     *
     * @return array<string, mixed>
     */
    abstract public function getDirty(): array;

    /**
     * Check if attribute is dirty.
     * Should be provided by the model class.
     *
     * @param  string|array<int, string>|null  $attributes
     */
    abstract public function isDirty(string|array|null $attributes = null): bool;
}

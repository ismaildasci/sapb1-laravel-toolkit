<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Models\Concerns;

/**
 * Tracks which attributes have been modified.
 * Enables partial updates (send only changed fields to API).
 */
trait HasDirtyTracking
{
    /**
     * Check if any attributes are dirty (modified).
     */
    public function isDirty(?string $key = null): bool
    {
        $changes = $this->getDirty();

        if ($key === null) {
            return ! empty($changes);
        }

        return array_key_exists($key, $changes);
    }

    /**
     * Check if model is clean (no modifications).
     */
    public function isClean(?string $key = null): bool
    {
        return ! $this->isDirty($key);
    }

    /**
     * Get all dirty (changed) attributes.
     *
     * @return array<string, mixed>
     */
    public function getDirty(): array
    {
        $dirty = [];

        foreach ($this->attributes as $key => $value) {
            if (! array_key_exists($key, $this->original)) {
                $dirty[$key] = $value;
            } elseif ($value !== $this->original[$key]) {
                $dirty[$key] = $value;
            }
        }

        return $dirty;
    }

    /**
     * Get changes (alias for getDirty).
     *
     * @return array<string, mixed>
     */
    public function getChanges(): array
    {
        return $this->getDirty();
    }

    /**
     * Check if attribute was changed.
     */
    public function wasChanged(?string $key = null): bool
    {
        return $this->isDirty($key);
    }

    /**
     * Sync changes after save.
     */
    public function syncChanges(): static
    {
        $this->syncOriginal();

        return $this;
    }

    /**
     * Discard changes and restore original values.
     */
    public function discardChanges(): static
    {
        $this->attributes = $this->original;

        return $this;
    }

    /**
     * Get only the changed attributes in SAP format.
     *
     * @return array<string, mixed>
     */
    public function getDirtyForSap(): array
    {
        $dirty = $this->getDirty();

        // Convert to SAP field names
        $sapDirty = [];
        foreach ($dirty as $key => $value) {
            $sapKey = $this->toSapFieldName($key);
            $sapDirty[$sapKey] = $this->prepareValueForSap($key, $value);
        }

        return $sapDirty;
    }

    /**
     * Convert camelCase to PascalCase for SAP.
     */
    protected function toSapFieldName(string $key): string
    {
        return ucfirst($key);
    }

    /**
     * Prepare value for SAP API.
     */
    protected function prepareValueForSap(string $key, mixed $value): mixed
    {
        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d');
        }

        if ($value instanceof \BackedEnum) {
            return $value->value;
        }

        if (is_array($value)) {
            return array_map(function ($item) {
                if ($item instanceof self) {
                    return $item->toArray();
                }

                return $item;
            }, $value);
        }

        return $value;
    }
}

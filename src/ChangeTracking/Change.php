<?php

declare(strict_types=1);

namespace SapB1\Toolkit\ChangeTracking;

use DateTimeImmutable;

/**
 * Represents a single detected change in SAP B1.
 */
final class Change
{
    /**
     * Create a new Change instance.
     *
     * @param  array<string, mixed>  $data
     * @param  array<string, mixed>|null  $previousData
     */
    public function __construct(
        public readonly string $entity,
        public readonly ChangeType $type,
        public readonly int|string $primaryKey,
        public readonly array $data,
        public readonly ?array $previousData = null,
        public readonly ?DateTimeImmutable $detectedAt = null,
    ) {}

    /**
     * Create a new "created" change.
     *
     * @param  array<string, mixed>  $data
     */
    public static function created(string $entity, int|string $primaryKey, array $data): self
    {
        return new self(
            entity: $entity,
            type: ChangeType::Created,
            primaryKey: $primaryKey,
            data: $data,
            detectedAt: new DateTimeImmutable,
        );
    }

    /**
     * Create a new "updated" change.
     *
     * @param  array<string, mixed>  $data
     * @param  array<string, mixed>|null  $previousData
     */
    public static function updated(string $entity, int|string $primaryKey, array $data, ?array $previousData = null): self
    {
        return new self(
            entity: $entity,
            type: ChangeType::Updated,
            primaryKey: $primaryKey,
            data: $data,
            previousData: $previousData,
            detectedAt: new DateTimeImmutable,
        );
    }

    /**
     * Create a new "deleted" change.
     *
     * @param  array<string, mixed>|null  $previousData
     */
    public static function deleted(string $entity, int|string $primaryKey, ?array $previousData = null): self
    {
        return new self(
            entity: $entity,
            type: ChangeType::Deleted,
            primaryKey: $primaryKey,
            data: [],
            previousData: $previousData,
            detectedAt: new DateTimeImmutable,
        );
    }

    /**
     * Check if this is a creation change.
     */
    public function isCreated(): bool
    {
        return $this->type === ChangeType::Created;
    }

    /**
     * Check if this is an update change.
     */
    public function isUpdated(): bool
    {
        return $this->type === ChangeType::Updated;
    }

    /**
     * Check if this is a deletion change.
     */
    public function isDeleted(): bool
    {
        return $this->type === ChangeType::Deleted;
    }

    /**
     * Get a specific field from the data.
     */
    public function get(string $field, mixed $default = null): mixed
    {
        return $this->data[$field] ?? $default;
    }

    /**
     * Check if a field has changed (for updates).
     */
    public function hasFieldChanged(string $field): bool
    {
        if ($this->previousData === null) {
            return false;
        }

        $current = $this->data[$field] ?? null;
        $previous = $this->previousData[$field] ?? null;

        return $current !== $previous;
    }

    /**
     * Get the changed fields (for updates).
     *
     * @return array<string, array{old: mixed, new: mixed}>
     */
    public function getChangedFields(): array
    {
        if ($this->previousData === null) {
            return [];
        }

        $changed = [];

        foreach ($this->data as $field => $newValue) {
            $oldValue = $this->previousData[$field] ?? null;

            if ($newValue !== $oldValue) {
                $changed[$field] = [
                    'old' => $oldValue,
                    'new' => $newValue,
                ];
            }
        }

        return $changed;
    }

    /**
     * Convert to array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'entity' => $this->entity,
            'type' => $this->type->value,
            'primary_key' => $this->primaryKey,
            'data' => $this->data,
            'previous_data' => $this->previousData,
            'detected_at' => $this->detectedAt?->format('Y-m-d H:i:s'),
        ];
    }
}

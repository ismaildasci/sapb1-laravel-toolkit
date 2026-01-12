<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Sync;

use DateTimeImmutable;

/**
 * Represents the result of a sync operation.
 */
final class SyncResult
{
    public readonly DateTimeImmutable $completedAt;

    /**
     * Create a new SyncResult instance.
     *
     * @param  string  $entity  The entity that was synced
     * @param  int  $created  Number of records created
     * @param  int  $updated  Number of records updated
     * @param  int  $deleted  Number of records soft deleted
     * @param  int  $failed  Number of records that failed to sync
     * @param  float  $duration  Duration in seconds
     * @param  bool  $success  Whether the sync was successful
     * @param  string|null  $error  Error message if failed
     * @param  array<string, mixed>  $metadata  Additional metadata
     */
    public function __construct(
        public readonly string $entity,
        public readonly int $created = 0,
        public readonly int $updated = 0,
        public readonly int $deleted = 0,
        public readonly int $failed = 0,
        public readonly float $duration = 0.0,
        public readonly bool $success = true,
        public readonly ?string $error = null,
        public readonly array $metadata = [],
    ) {
        $this->completedAt = new DateTimeImmutable;
    }

    /**
     * Create a successful result.
     */
    public static function success(
        string $entity,
        int $created = 0,
        int $updated = 0,
        int $deleted = 0,
        float $duration = 0.0,
    ): self {
        return new self(
            entity: $entity,
            created: $created,
            updated: $updated,
            deleted: $deleted,
            duration: $duration,
            success: true,
        );
    }

    /**
     * Create a failed result.
     */
    public static function failed(string $entity, string $error, float $duration = 0.0): self
    {
        return new self(
            entity: $entity,
            duration: $duration,
            success: false,
            error: $error,
        );
    }

    /**
     * Get total number of records processed.
     */
    public function total(): int
    {
        return $this->created + $this->updated + $this->deleted;
    }

    /**
     * Get total number of records synced (created + updated).
     */
    public function synced(): int
    {
        return $this->created + $this->updated;
    }

    /**
     * Check if any records were affected.
     */
    public function hasChanges(): bool
    {
        return $this->total() > 0;
    }

    /**
     * Check if the sync failed.
     */
    public function hasFailed(): bool
    {
        return ! $this->success;
    }

    /**
     * Create a copy with added deleted count.
     */
    public function withDeleted(int $deleted): self
    {
        return new self(
            entity: $this->entity,
            created: $this->created,
            updated: $this->updated,
            deleted: $deleted,
            failed: $this->failed,
            duration: $this->duration,
            success: $this->success,
            error: $this->error,
            metadata: $this->metadata,
        );
    }

    /**
     * Create a copy with added metadata.
     *
     * @param  array<string, mixed>  $metadata
     */
    public function withMetadata(array $metadata): self
    {
        return new self(
            entity: $this->entity,
            created: $this->created,
            updated: $this->updated,
            deleted: $this->deleted,
            failed: $this->failed,
            duration: $this->duration,
            success: $this->success,
            error: $this->error,
            metadata: array_merge($this->metadata, $metadata),
        );
    }

    /**
     * Get human-readable summary.
     */
    public function summary(): string
    {
        if (! $this->success) {
            return "Failed: {$this->error}";
        }

        $parts = [];

        if ($this->created > 0) {
            $parts[] = "{$this->created} created";
        }

        if ($this->updated > 0) {
            $parts[] = "{$this->updated} updated";
        }

        if ($this->deleted > 0) {
            $parts[] = "{$this->deleted} deleted";
        }

        if (empty($parts)) {
            return 'No changes';
        }

        $duration = number_format($this->duration, 2);

        return implode(', ', $parts)." in {$duration}s";
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
            'success' => $this->success,
            'created' => $this->created,
            'updated' => $this->updated,
            'deleted' => $this->deleted,
            'failed' => $this->failed,
            'total' => $this->total(),
            'duration' => $this->duration,
            'error' => $this->error,
            'completed_at' => $this->completedAt->format('Y-m-d H:i:s'),
            'metadata' => $this->metadata,
        ];
    }
}

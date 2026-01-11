<?php

declare(strict_types=1);

namespace SapB1\Toolkit\ChangeTracking\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use SapB1\Toolkit\ChangeTracking\Change;
use SapB1\Toolkit\ChangeTracking\ChangeType;

/**
 * Event dispatched when a single entity change is detected.
 */
final class EntityChangeDetected
{
    use Dispatchable;
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public readonly Change $change,
    ) {}

    /**
     * Get the entity name.
     */
    public function entity(): string
    {
        return $this->change->entity;
    }

    /**
     * Get the change type.
     */
    public function type(): ChangeType
    {
        return $this->change->type;
    }

    /**
     * Get the primary key.
     */
    public function primaryKey(): int|string
    {
        return $this->change->primaryKey;
    }

    /**
     * Get the data.
     *
     * @return array<string, mixed>
     */
    public function data(): array
    {
        return $this->change->data;
    }

    /**
     * Check if this is a creation.
     */
    public function isCreated(): bool
    {
        return $this->change->isCreated();
    }

    /**
     * Check if this is an update.
     */
    public function isUpdated(): bool
    {
        return $this->change->isUpdated();
    }

    /**
     * Check if this is a deletion.
     */
    public function isDeleted(): bool
    {
        return $this->change->isDeleted();
    }
}

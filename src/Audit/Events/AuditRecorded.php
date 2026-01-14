<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Audit\Events;

use Illuminate\Foundation\Events\Dispatchable;
use SapB1\Toolkit\Audit\AuditEntry;

/**
 * Event dispatched when an audit entry is successfully recorded.
 */
class AuditRecorded
{
    use Dispatchable;

    public function __construct(
        public readonly AuditEntry $entry,
        public readonly string $driver,
    ) {}

    /**
     * Get the entity type.
     */
    public function getEntityType(): string
    {
        return $this->entry->entityType;
    }

    /**
     * Get the entity ID.
     */
    public function getEntityId(): string|int
    {
        return $this->entry->entityId;
    }

    /**
     * Get the event type (created, updated, deleted, etc.).
     */
    public function getEvent(): string
    {
        return $this->entry->event;
    }
}

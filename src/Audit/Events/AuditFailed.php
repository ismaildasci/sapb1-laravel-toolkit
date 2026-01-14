<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Audit\Events;

use Illuminate\Foundation\Events\Dispatchable;
use SapB1\Toolkit\Audit\AuditEntry;
use Throwable;

/**
 * Event dispatched when audit logging fails.
 */
class AuditFailed
{
    use Dispatchable;

    public function __construct(
        public readonly AuditEntry $entry,
        public readonly string $driver,
        public readonly ?Throwable $exception = null,
        public readonly ?string $reason = null,
    ) {}

    /**
     * Get the error message.
     */
    public function getErrorMessage(): string
    {
        if ($this->exception !== null) {
            return $this->exception->getMessage();
        }

        return $this->reason ?? 'Unknown error';
    }

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
}

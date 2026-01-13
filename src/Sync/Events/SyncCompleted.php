<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Sync\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use SapB1\Toolkit\Sync\SyncResult;

/**
 * Event dispatched when a sync operation completes successfully.
 */
final class SyncCompleted
{
    use Dispatchable;
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public readonly string $entity,
        public readonly SyncResult $result,
    ) {}

    /**
     * Get the number of created records.
     */
    public function created(): int
    {
        return $this->result->created;
    }

    /**
     * Get the number of updated records.
     */
    public function updated(): int
    {
        return $this->result->updated;
    }

    /**
     * Get the number of deleted records.
     */
    public function deleted(): int
    {
        return $this->result->deleted;
    }

    /**
     * Get the total number of affected records.
     */
    public function total(): int
    {
        return $this->result->total();
    }

    /**
     * Get the sync duration in seconds.
     */
    public function duration(): float
    {
        return $this->result->duration;
    }
}

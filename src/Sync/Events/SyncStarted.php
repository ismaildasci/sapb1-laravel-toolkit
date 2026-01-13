<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Sync\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Event dispatched when a sync operation starts.
 */
final class SyncStarted
{
    use Dispatchable;
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public readonly string $entity,
        public readonly string $syncType,
        public readonly ?string $since = null,
    ) {}

    /**
     * Check if this is a full sync.
     */
    public function isFullSync(): bool
    {
        return $this->syncType === 'full';
    }

    /**
     * Check if this is an incremental sync.
     */
    public function isIncrementalSync(): bool
    {
        return $this->syncType === 'incremental';
    }
}

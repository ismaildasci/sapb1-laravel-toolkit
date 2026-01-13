<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Sync\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Event dispatched during sync to report progress.
 */
final class SyncProgress
{
    use Dispatchable;
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public readonly string $entity,
        public readonly int $processed,
        public readonly int $total,
        public readonly int $created,
        public readonly int $updated,
    ) {}

    /**
     * Get the progress percentage.
     */
    public function percentage(): float
    {
        if ($this->total === 0) {
            return 100.0;
        }

        return round(($this->processed / $this->total) * 100, 2);
    }

    /**
     * Check if sync is complete.
     */
    public function isComplete(): bool
    {
        return $this->processed >= $this->total;
    }

    /**
     * Get remaining records to process.
     */
    public function remaining(): int
    {
        return max(0, $this->total - $this->processed);
    }
}

<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Sync\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Event dispatched when a sync operation fails.
 */
final class SyncFailed
{
    use Dispatchable;
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public readonly string $entity,
        public readonly string $error,
        public readonly ?float $duration = null,
        public readonly ?\Throwable $exception = null,
    ) {}

    /**
     * Get the error message.
     */
    public function message(): string
    {
        return $this->error;
    }

    /**
     * Check if exception details are available.
     */
    public function hasException(): bool
    {
        return $this->exception !== null;
    }

    /**
     * Get the exception class name if available.
     */
    public function exceptionClass(): ?string
    {
        return $this->exception !== null ? $this->exception::class : null;
    }
}

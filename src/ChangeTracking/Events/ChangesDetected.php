<?php

declare(strict_types=1);

namespace SapB1\Toolkit\ChangeTracking\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use SapB1\Toolkit\ChangeTracking\Change;

/**
 * Event dispatched when multiple changes are detected.
 */
final class ChangesDetected
{
    use Dispatchable;
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  Collection<int, Change>  $changes
     */
    public function __construct(
        public readonly string $entity,
        public readonly Collection $changes,
    ) {}

    /**
     * Get the count of changes.
     */
    public function count(): int
    {
        return $this->changes->count();
    }

    /**
     * Get only created changes.
     *
     * @return Collection<int, Change>
     */
    public function created(): Collection
    {
        return $this->changes->filter(fn (Change $change) => $change->isCreated());
    }

    /**
     * Get only updated changes.
     *
     * @return Collection<int, Change>
     */
    public function updated(): Collection
    {
        return $this->changes->filter(fn (Change $change) => $change->isUpdated());
    }

    /**
     * Get only deleted changes.
     *
     * @return Collection<int, Change>
     */
    public function deleted(): Collection
    {
        return $this->changes->filter(fn (Change $change) => $change->isDeleted());
    }
}

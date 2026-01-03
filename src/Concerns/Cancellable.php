<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Concerns;

trait Cancellable
{
    protected bool $cancelled = false;

    public function isCancelled(): bool
    {
        return $this->cancelled;
    }

    public function markAsCancelled(): static
    {
        $this->cancelled = true;

        return $this;
    }

    public function canCancel(): bool
    {
        return ! $this->cancelled;
    }
}

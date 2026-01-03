<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Concerns;

trait Closable
{
    protected bool $closed = false;

    public function isClosed(): bool
    {
        return $this->closed;
    }

    public function markAsClosed(): static
    {
        $this->closed = true;

        return $this;
    }

    public function canClose(): bool
    {
        return ! $this->closed;
    }
}

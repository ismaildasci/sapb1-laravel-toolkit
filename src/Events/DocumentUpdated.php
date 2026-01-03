<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Events;

use Illuminate\Foundation\Events\Dispatchable;

class DocumentUpdated
{
    use Dispatchable;

    /**
     * @param  array<string, mixed>  $changes
     */
    public function __construct(
        public readonly string $entity,
        public readonly int $docEntry,
        public readonly array $changes = [],
    ) {}
}

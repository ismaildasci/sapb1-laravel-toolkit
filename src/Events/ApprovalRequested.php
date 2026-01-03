<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Events;

use Illuminate\Foundation\Events\Dispatchable;

class ApprovalRequested
{
    use Dispatchable;

    public function __construct(
        public readonly int $approvalRequestCode,
        public readonly string $objectType,
        public readonly int $objectEntry,
    ) {}
}

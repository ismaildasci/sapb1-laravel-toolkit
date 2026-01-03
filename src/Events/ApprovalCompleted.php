<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Events;

use Illuminate\Foundation\Events\Dispatchable;
use SapB1\Toolkit\Enums\ApprovalStatus;

class ApprovalCompleted
{
    use Dispatchable;

    public function __construct(
        public readonly int $approvalRequestCode,
        public readonly string $objectType,
        public readonly int $objectEntry,
        public readonly ApprovalStatus $status,
    ) {}
}

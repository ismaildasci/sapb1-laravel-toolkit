<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Enums;

enum ApprovalStatus: string
{
    case Pending = 'astPending';
    case Approved = 'astApproved';
    case Rejected = 'astRejected';
    case Generated = 'astGenerated';
    case NotApplicable = 'astNotApplicable';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Approved => 'Approved',
            self::Rejected => 'Rejected',
            self::Generated => 'Generated',
            self::NotApplicable => 'Not Applicable',
        };
    }

    public function isPending(): bool
    {
        return $this === self::Pending;
    }

    public function isApproved(): bool
    {
        return $this === self::Approved;
    }

    public function isFinal(): bool
    {
        return in_array($this, [self::Approved, self::Rejected], true);
    }
}

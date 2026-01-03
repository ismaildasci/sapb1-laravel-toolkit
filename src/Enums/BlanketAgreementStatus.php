<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Enums;

enum BlanketAgreementStatus: string
{
    case Approved = 'asApproved';
    case OnHold = 'asOnHold';
    case Draft = 'asDraft';
    case Terminated = 'asTerminated';

    public function label(): string
    {
        return match ($this) {
            self::Approved => 'Approved',
            self::OnHold => 'On Hold',
            self::Draft => 'Draft',
            self::Terminated => 'Terminated',
        };
    }

    public function isApproved(): bool
    {
        return $this === self::Approved;
    }

    public function isActive(): bool
    {
        return in_array($this, [self::Approved, self::OnHold], true);
    }

    public function isFinal(): bool
    {
        return $this === self::Terminated;
    }
}

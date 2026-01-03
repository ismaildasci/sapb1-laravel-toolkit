<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Enums;

enum DocumentStatus: string
{
    case Open = 'bost_Open';
    case Closed = 'bost_Close';
    case Cancelled = 'bost_Cancelled';
    case Pending = 'bost_Pending';
    case Delivered = 'bost_Delivered';

    public function label(): string
    {
        return match ($this) {
            self::Open => 'Open',
            self::Closed => 'Closed',
            self::Cancelled => 'Cancelled',
            self::Pending => 'Pending',
            self::Delivered => 'Delivered',
        };
    }

    public function isEditable(): bool
    {
        return $this === self::Open;
    }

    public function isFinal(): bool
    {
        return in_array($this, [self::Closed, self::Cancelled], true);
    }

    public function canClose(): bool
    {
        return $this === self::Open;
    }

    public function canCancel(): bool
    {
        return $this === self::Open;
    }
}

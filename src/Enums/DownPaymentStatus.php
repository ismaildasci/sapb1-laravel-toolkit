<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Enums;

enum DownPaymentStatus: string
{
    case Open = 'so_Open';
    case Closed = 'so_Closed';

    public function label(): string
    {
        return match ($this) {
            self::Open => 'Open',
            self::Closed => 'Closed',
        };
    }

    public function isOpen(): bool
    {
        return $this === self::Open;
    }

    public function isClosed(): bool
    {
        return $this === self::Closed;
    }
}

<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Enums;

enum ProductionOrderStatus: string
{
    case Planned = 'boposPlanned';
    case Released = 'boposReleased';
    case Closed = 'boposClosed';
    case Cancelled = 'boposCancelled';

    public function label(): string
    {
        return match ($this) {
            self::Planned => 'Planned',
            self::Released => 'Released',
            self::Closed => 'Closed',
            self::Cancelled => 'Cancelled',
        };
    }

    public function canRelease(): bool
    {
        return $this === self::Planned;
    }

    public function canClose(): bool
    {
        return $this === self::Released;
    }
}

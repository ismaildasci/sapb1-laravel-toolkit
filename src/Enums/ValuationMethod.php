<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Enums;

enum ValuationMethod: string
{
    case MovingAverage = 'bvm_MovingAverage';
    case Standard = 'bvm_Standard';
    case FIFO = 'bvm_FIFO';
    case SerialNumber = 'bvm_SNB';

    public function label(): string
    {
        return match ($this) {
            self::MovingAverage => 'Moving Average',
            self::Standard => 'Standard',
            self::FIFO => 'FIFO',
            self::SerialNumber => 'Serial/Batch',
        };
    }
}

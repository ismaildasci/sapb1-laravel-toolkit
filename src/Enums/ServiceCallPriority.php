<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Enums;

enum ServiceCallPriority: string
{
    case Low = 'scp_Low';
    case Medium = 'scp_Medium';
    case High = 'scp_High';

    public function label(): string
    {
        return match ($this) {
            self::Low => 'Low',
            self::Medium => 'Medium',
            self::High => 'High',
        };
    }
}

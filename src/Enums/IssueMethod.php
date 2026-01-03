<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Enums;

enum IssueMethod: string
{
    case Backflush = 'bomimBackflush';
    case Manual = 'bomimManual';

    public function label(): string
    {
        return match ($this) {
            self::Backflush => 'Backflush',
            self::Manual => 'Manual',
        };
    }
}

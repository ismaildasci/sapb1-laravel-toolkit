<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Enums;

enum ResourceType: string
{
    case Machine = 'rtMachine';
    case Labor = 'rtLabor';
    case Other = 'rtOther';

    public function label(): string
    {
        return match ($this) {
            self::Machine => 'Machine',
            self::Labor => 'Labor',
            self::Other => 'Other',
        };
    }
}

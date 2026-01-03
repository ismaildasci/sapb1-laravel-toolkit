<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Enums;

enum ProductionOrderType: string
{
    case Standard = 'bopotStandard';
    case Special = 'bopotSpecial';
    case Disassembly = 'bopotDisassembly';

    public function label(): string
    {
        return match ($this) {
            self::Standard => 'Standard',
            self::Special => 'Special',
            self::Disassembly => 'Disassembly',
        };
    }
}

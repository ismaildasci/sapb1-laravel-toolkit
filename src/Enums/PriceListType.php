<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Enums;

enum PriceListType: string
{
    case Manual = 'plm_Manual';
    case AutoUpdate = 'plm_AutoUpdate';

    public function label(): string
    {
        return match ($this) {
            self::Manual => 'Manual',
            self::AutoUpdate => 'Auto Update',
        };
    }
}

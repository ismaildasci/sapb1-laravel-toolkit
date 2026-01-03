<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Enums;

enum GroupType: string
{
    case Customer = 'gt_Customer';
    case Supplier = 'gt_Supplier';

    public function label(): string
    {
        return match ($this) {
            self::Customer => 'Customer Group',
            self::Supplier => 'Supplier Group',
        };
    }
}

<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Enums;

enum ShippingType: int
{
    case Company = 1;
    case Customer = 2;
    case ThirdParty = 3;

    public function label(): string
    {
        return match ($this) {
            self::Company => 'Company',
            self::Customer => 'Customer',
            self::ThirdParty => 'Third Party',
        };
    }
}

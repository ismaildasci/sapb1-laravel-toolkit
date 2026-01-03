<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Enums;

enum ItemType: string
{
    case Items = 'itItems';
    case Labor = 'itLabor';
    case Travel = 'itTravel';
    case FixedAssets = 'itFixedAssets';

    public function label(): string
    {
        return match ($this) {
            self::Items => 'Items',
            self::Labor => 'Labor',
            self::Travel => 'Travel',
            self::FixedAssets => 'Fixed Assets',
        };
    }
}

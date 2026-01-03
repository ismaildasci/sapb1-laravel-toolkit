<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Enums;

enum BlanketAgreementMethod: string
{
    case Monetary = 'bamMonetary';
    case Quantity = 'bamQuantity';

    public function label(): string
    {
        return match ($this) {
            self::Monetary => 'Monetary',
            self::Quantity => 'Quantity',
        };
    }

    public function isMonetary(): bool
    {
        return $this === self::Monetary;
    }

    public function isQuantity(): bool
    {
        return $this === self::Quantity;
    }
}

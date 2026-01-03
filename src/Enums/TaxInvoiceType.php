<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Enums;

enum TaxInvoiceType: string
{
    case Regular = 'titRegular';
    case Corrective = 'titCorrective';
    case Simplified = 'titSimplified';

    public function label(): string
    {
        return match ($this) {
            self::Regular => 'Regular',
            self::Corrective => 'Corrective',
            self::Simplified => 'Simplified',
        };
    }

    public function isRegular(): bool
    {
        return $this === self::Regular;
    }

    public function isCorrective(): bool
    {
        return $this === self::Corrective;
    }
}

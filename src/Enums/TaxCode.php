<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Enums;

/**
 * Common Turkish tax codes used in SAP B1.
 */
enum TaxCode: string
{
    // VAT (KDV) Rates
    case KDV0 = 'KDV0';
    case KDV1 = 'KDV1';
    case KDV8 = 'KDV8';
    case KDV10 = 'KDV10';
    case KDV18 = 'KDV18';
    case KDV20 = 'KDV20';

    // Withholding Tax (Stopaj)
    case STOPAJ = 'STOPAJ';

    // Exempt
    case EXEMPT = 'EXEMPT';
    case ISTISNA = 'ISTISNA';

    public function label(): string
    {
        return match ($this) {
            self::KDV0 => 'KDV %0',
            self::KDV1 => 'KDV %1',
            self::KDV8 => 'KDV %8',
            self::KDV10 => 'KDV %10',
            self::KDV18 => 'KDV %18',
            self::KDV20 => 'KDV %20',
            self::STOPAJ => 'Stopaj',
            self::EXEMPT => 'Exempt',
            self::ISTISNA => 'İstisna',
        };
    }

    public function rate(): float
    {
        return match ($this) {
            self::KDV0, self::EXEMPT, self::ISTISNA => 0.0,
            self::KDV1 => 1.0,
            self::KDV8 => 8.0,
            self::KDV10 => 10.0,
            self::KDV18 => 18.0,
            self::KDV20 => 20.0,
            self::STOPAJ => 0.0,
        };
    }

    public function isExempt(): bool
    {
        return in_array($this, [self::KDV0, self::EXEMPT, self::ISTISNA], true);
    }

    public function isStandard(): bool
    {
        return in_array($this, [self::KDV18, self::KDV20], true);
    }

    public function isReduced(): bool
    {
        return in_array($this, [self::KDV1, self::KDV8, self::KDV10], true);
    }
}

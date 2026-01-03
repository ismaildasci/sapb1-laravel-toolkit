<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Enums;

/**
 * Common currencies used in SAP B1.
 */
enum Currency: string
{
    case TRY = 'TRY';
    case USD = 'USD';
    case EUR = 'EUR';
    case GBP = 'GBP';
    case CHF = 'CHF';
    case JPY = 'JPY';
    case CNY = 'CNY';
    case RUB = 'RUB';
    case AED = 'AED';
    case SAR = 'SAR';

    public function label(): string
    {
        return match ($this) {
            self::TRY => 'Turkish Lira',
            self::USD => 'US Dollar',
            self::EUR => 'Euro',
            self::GBP => 'British Pound',
            self::CHF => 'Swiss Franc',
            self::JPY => 'Japanese Yen',
            self::CNY => 'Chinese Yuan',
            self::RUB => 'Russian Ruble',
            self::AED => 'UAE Dirham',
            self::SAR => 'Saudi Riyal',
        };
    }

    public function symbol(): string
    {
        return match ($this) {
            self::TRY => '₺',
            self::USD => '$',
            self::EUR => '€',
            self::GBP => '£',
            self::CHF => 'CHF',
            self::JPY => '¥',
            self::CNY => '¥',
            self::RUB => '₽',
            self::AED => 'د.إ',
            self::SAR => '﷼',
        };
    }

    public function decimalPlaces(): int
    {
        return match ($this) {
            self::JPY => 0,
            default => 2,
        };
    }

    public function isLocal(): bool
    {
        return $this === self::TRY;
    }

    public function isForeign(): bool
    {
        return $this !== self::TRY;
    }

    /**
     * @return array<Currency>
     */
    public static function majorCurrencies(): array
    {
        return [
            self::TRY,
            self::USD,
            self::EUR,
            self::GBP,
        ];
    }
}

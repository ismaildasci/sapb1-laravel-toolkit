<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Enums;

/**
 * Common units of measure used in SAP B1.
 */
enum UnitOfMeasure: string
{
    // Quantity units
    case PCS = 'PCS';
    case EA = 'EA';
    case SET = 'SET';
    case BOX = 'BOX';
    case PKG = 'PKG';
    case PAL = 'PAL';

    // Weight units
    case KG = 'KG';
    case GR = 'GR';
    case TON = 'TON';
    case LB = 'LB';

    // Volume units
    case LT = 'LT';
    case ML = 'ML';
    case M3 = 'M3';
    case GAL = 'GAL';

    // Length units
    case M = 'M';
    case CM = 'CM';
    case MM = 'MM';
    case FT = 'FT';
    case IN = 'IN';

    // Area units
    case M2 = 'M2';

    // Time units
    case HR = 'HR';
    case DAY = 'DAY';
    case MON = 'MON';

    public function label(): string
    {
        return match ($this) {
            self::PCS => 'Pieces',
            self::EA => 'Each',
            self::SET => 'Set',
            self::BOX => 'Box',
            self::PKG => 'Package',
            self::PAL => 'Pallet',
            self::KG => 'Kilogram',
            self::GR => 'Gram',
            self::TON => 'Ton',
            self::LB => 'Pound',
            self::LT => 'Liter',
            self::ML => 'Milliliter',
            self::M3 => 'Cubic Meter',
            self::GAL => 'Gallon',
            self::M => 'Meter',
            self::CM => 'Centimeter',
            self::MM => 'Millimeter',
            self::FT => 'Feet',
            self::IN => 'Inch',
            self::M2 => 'Square Meter',
            self::HR => 'Hour',
            self::DAY => 'Day',
            self::MON => 'Month',
        };
    }

    public function labelTr(): string
    {
        return match ($this) {
            self::PCS => 'Adet',
            self::EA => 'Adet',
            self::SET => 'Set',
            self::BOX => 'Kutu',
            self::PKG => 'Paket',
            self::PAL => 'Palet',
            self::KG => 'Kilogram',
            self::GR => 'Gram',
            self::TON => 'Ton',
            self::LB => 'Libre',
            self::LT => 'Litre',
            self::ML => 'Mililitre',
            self::M3 => 'Metreküp',
            self::GAL => 'Galon',
            self::M => 'Metre',
            self::CM => 'Santimetre',
            self::MM => 'Milimetre',
            self::FT => 'Feet',
            self::IN => 'İnç',
            self::M2 => 'Metrekare',
            self::HR => 'Saat',
            self::DAY => 'Gün',
            self::MON => 'Ay',
        };
    }

    public function category(): string
    {
        return match ($this) {
            self::PCS, self::EA, self::SET, self::BOX, self::PKG, self::PAL => 'quantity',
            self::KG, self::GR, self::TON, self::LB => 'weight',
            self::LT, self::ML, self::M3, self::GAL => 'volume',
            self::M, self::CM, self::MM, self::FT, self::IN => 'length',
            self::M2 => 'area',
            self::HR, self::DAY, self::MON => 'time',
        };
    }

    public function isQuantity(): bool
    {
        return $this->category() === 'quantity';
    }

    public function isWeight(): bool
    {
        return $this->category() === 'weight';
    }

    public function isVolume(): bool
    {
        return $this->category() === 'volume';
    }

    public function isLength(): bool
    {
        return $this->category() === 'length';
    }

    public function isTime(): bool
    {
        return $this->category() === 'time';
    }

    /**
     * @return array<UnitOfMeasure>
     */
    public static function quantityUnits(): array
    {
        return [self::PCS, self::EA, self::SET, self::BOX, self::PKG, self::PAL];
    }

    /**
     * @return array<UnitOfMeasure>
     */
    public static function weightUnits(): array
    {
        return [self::KG, self::GR, self::TON, self::LB];
    }

    /**
     * @return array<UnitOfMeasure>
     */
    public static function volumeUnits(): array
    {
        return [self::LT, self::ML, self::M3, self::GAL];
    }
}

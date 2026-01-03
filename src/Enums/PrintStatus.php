<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Enums;

enum PrintStatus: string
{
    case NotPrinted = 'bop_No';
    case Printed = 'bop_Yes';
    case PrintedOnce = 'bop_Once';

    public function label(): string
    {
        return match ($this) {
            self::NotPrinted => 'Not Printed',
            self::Printed => 'Printed',
            self::PrintedOnce => 'Printed Once',
        };
    }

    public function isPrinted(): bool
    {
        return $this !== self::NotPrinted;
    }
}

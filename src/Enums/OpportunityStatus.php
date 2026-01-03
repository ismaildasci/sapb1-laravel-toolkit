<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Enums;

enum OpportunityStatus: string
{
    case Open = 'sos_Open';
    case Lost = 'sos_Lost';
    case Won = 'sos_Sold';

    public function label(): string
    {
        return match ($this) {
            self::Open => 'Open',
            self::Lost => 'Lost',
            self::Won => 'Won',
        };
    }
}

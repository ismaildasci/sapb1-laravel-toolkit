<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Enums;

enum CampaignStatus: string
{
    case Draft = 'cs_Draft';
    case Active = 'cs_Active';
    case Finished = 'cs_Finished';
    case Cancelled = 'cs_Cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Active => 'Active',
            self::Finished => 'Finished',
            self::Cancelled => 'Cancelled',
        };
    }
}

<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Enums;

enum PaymentTermsType: string
{
    case Net = 'ptNet';
    case MonthEnd = 'ptMonthEnd';
    case HalfMonth = 'ptHalfMonth';
    case FixedDay = 'ptFixedDay';

    public function label(): string
    {
        return match ($this) {
            self::Net => 'Net Days',
            self::MonthEnd => 'Month End',
            self::HalfMonth => 'Half Month',
            self::FixedDay => 'Fixed Day',
        };
    }
}

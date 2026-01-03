<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Enums;

enum UserType: string
{
    case Regular = 'ut_Regular';
    case LimitedAccess = 'ut_LimitedAccess';
    case ExternalUser = 'ut_ExternalUser';

    public function label(): string
    {
        return match ($this) {
            self::Regular => 'Regular',
            self::LimitedAccess => 'Limited Access',
            self::ExternalUser => 'External User',
        };
    }
}

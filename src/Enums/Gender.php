<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Enums;

enum Gender: string
{
    case Male = 'gt_Male';
    case Female = 'gt_Female';
    case Undefined = 'gt_Undefined';

    public function label(): string
    {
        return match ($this) {
            self::Male => 'Male',
            self::Female => 'Female',
            self::Undefined => 'Undefined',
        };
    }
}

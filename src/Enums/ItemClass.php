<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Enums;

enum ItemClass: string
{
    case Service = 'itcService';
    case Material = 'itcMaterial';

    public function label(): string
    {
        return match ($this) {
            self::Service => 'Service',
            self::Material => 'Material',
        };
    }
}

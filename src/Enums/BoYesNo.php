<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Enums;

enum BoYesNo: string
{
    case Yes = 'tYES';
    case No = 'tNO';

    public static function fromBool(bool $value): self
    {
        return $value ? self::Yes : self::No;
    }

    public function toBool(): bool
    {
        return $this === self::Yes;
    }

    public function label(): string
    {
        return match ($this) {
            self::Yes => 'Yes',
            self::No => 'No',
        };
    }
}

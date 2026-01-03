<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Enums;

enum EmployeeStatusType: string
{
    case Active = 'etsActive';
    case Inactive = 'etsInactive';
    case Terminated = 'etsTerminated';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Inactive => 'Inactive',
            self::Terminated => 'Terminated',
        };
    }

    public function isActive(): bool
    {
        return $this === self::Active;
    }
}

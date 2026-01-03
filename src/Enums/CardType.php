<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Enums;

enum CardType: string
{
    case Customer = 'cCustomer';
    case Supplier = 'cSupplier';
    case Lead = 'cLid';

    public function label(): string
    {
        return match ($this) {
            self::Customer => 'Customer',
            self::Supplier => 'Supplier',
            self::Lead => 'Lead',
        };
    }

    public function isCustomer(): bool
    {
        return $this === self::Customer;
    }

    public function isSupplier(): bool
    {
        return $this === self::Supplier;
    }

    public function isLead(): bool
    {
        return $this === self::Lead;
    }
}

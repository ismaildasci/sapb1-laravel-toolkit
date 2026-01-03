<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Enums;

enum PaymentMethod: string
{
    case Cash = 'Cash';
    case Check = 'Check';
    case BankTransfer = 'BankTransfer';
    case CreditCard = 'CreditCard';

    public function label(): string
    {
        return match ($this) {
            self::Cash => 'Cash',
            self::Check => 'Check',
            self::BankTransfer => 'Bank Transfer',
            self::CreditCard => 'Credit Card',
        };
    }
}

<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Enums;

enum AccountType: string
{
    case Asset = 'at_Asset';
    case Liability = 'at_Liability';
    case Equity = 'at_Equity';
    case Revenue = 'at_Revenues';
    case Expense = 'at_Expenses';

    public function label(): string
    {
        return match ($this) {
            self::Asset => 'Asset',
            self::Liability => 'Liability',
            self::Equity => 'Equity',
            self::Revenue => 'Revenue',
            self::Expense => 'Expense',
        };
    }
}

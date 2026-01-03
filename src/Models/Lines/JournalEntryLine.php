<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Models\Lines;

use SapB1\Toolkit\Models\SapB1Model;

/**
 * Journal Entry Line model (embedded in journal entries).
 */
class JournalEntryLine extends SapB1Model
{
    protected static string $entity = '';

    protected static string $primaryKey = 'LineID';

    protected array $fillable = [
        'AccountCode',
        'Debit',
        'Credit',
        'FCCurrency',
        'FCDebit',
        'FCCredit',
        'ShortName',
        'LineMemo',
        'CostingCode',
        'CostingCode2',
        'CostingCode3',
        'CostingCode4',
        'CostingCode5',
        'Project',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'LineID' => 'integer',
            'Debit' => 'decimal:2',
            'Credit' => 'decimal:2',
            'FCDebit' => 'decimal:2',
            'FCCredit' => 'decimal:2',
        ];
    }
}

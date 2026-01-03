<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Models\Lines;

use SapB1\Toolkit\Models\SapB1Model;

/**
 * Payment Invoice line model (embedded in payments).
 */
class PaymentInvoice extends SapB1Model
{
    protected static string $entity = '';

    protected static string $primaryKey = 'LineNum';

    protected array $fillable = [
        'DocEntry',
        'DocNum',
        'InvoiceType',
        'SumApplied',
        'AppliedFC',
        'DiscountPercent',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'LineNum' => 'integer',
            'DocEntry' => 'integer',
            'DocNum' => 'integer',
            'SumApplied' => 'decimal:2',
            'AppliedFC' => 'decimal:2',
            'DiscountPercent' => 'decimal:2',
        ];
    }
}

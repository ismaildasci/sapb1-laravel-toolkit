<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Models\Lines;

use SapB1\Toolkit\Models\Inventory\Item;
use SapB1\Toolkit\Models\Relations\BelongsTo;
use SapB1\Toolkit\Models\SapB1Model;

/**
 * Blanket Agreement Item Line model.
 */
class BlanketAgreementItemLine extends SapB1Model
{
    protected static string $entity = '';

    protected static string $primaryKey = 'LineNo';

    protected array $fillable = [
        'ItemNo',
        'ItemDescription',
        'ItemGroup',
        'PlannedQuantity',
        'PlannedAmountLC',
        'PlannedAmountFC',
        'UoMEntry',
        'UoMCode',
        'UnitPrice',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'LineNo' => 'integer',
            'PlannedQuantity' => 'decimal:4',
            'PlannedAmountLC' => 'decimal:2',
            'PlannedAmountFC' => 'decimal:2',
            'UnitPrice' => 'decimal:4',
            'CumulativeQuantity' => 'decimal:4',
            'CumulativeAmountLC' => 'decimal:2',
            'CumulativeAmountFC' => 'decimal:2',
        ];
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'ItemNo', 'ItemCode');
    }
}

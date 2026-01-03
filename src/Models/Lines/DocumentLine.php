<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Models\Lines;

use SapB1\Toolkit\Models\Inventory\Item;
use SapB1\Toolkit\Models\Inventory\Warehouse;
use SapB1\Toolkit\Models\Relations\BelongsTo;
use SapB1\Toolkit\Models\SapB1Model;

/**
 * Document Line model (embedded in documents).
 */
class DocumentLine extends SapB1Model
{
    protected static string $entity = '';

    protected static string $primaryKey = 'LineNum';

    protected array $fillable = [
        'ItemCode',
        'ItemDescription',
        'Quantity',
        'Price',
        'Currency',
        'DiscountPercent',
        'WarehouseCode',
        'TaxCode',
        'UnitPrice',
        'LineTotal',
        'BaseType',
        'BaseEntry',
        'BaseLine',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'LineNum' => 'integer',
            'Quantity' => 'decimal:4',
            'Price' => 'decimal:4',
            'UnitPrice' => 'decimal:4',
            'DiscountPercent' => 'decimal:2',
            'LineTotal' => 'decimal:2',
            'VatSum' => 'decimal:2',
            'BaseType' => 'integer',
            'BaseEntry' => 'integer',
            'BaseLine' => 'integer',
        ];
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'ItemCode', 'ItemCode');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'WarehouseCode', 'WarehouseCode');
    }
}

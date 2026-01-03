<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Models\Inventory;

use SapB1\Toolkit\Enums\ItemType;
use SapB1\Toolkit\Models\QueryBuilder;
use SapB1\Toolkit\Models\Relations\BelongsTo;
use SapB1\Toolkit\Models\SapB1Model;

/**
 * Item model.
 */
class Item extends SapB1Model
{
    protected static string $entity = 'Items';

    protected static string $primaryKey = 'ItemCode';

    protected array $fillable = [
        'ItemCode',
        'ItemName',
        'ForeignName',
        'ItemsGroupCode',
        'BarCode',
        'ItemType',
        'PurchaseItem',
        'SalesItem',
        'InventoryItem',
        'DefaultWarehouse',
        'Manufacturer',
        'QuantityOnStock',
        'QuantityOrderedFromVendors',
        'QuantityOrderedByCustomers',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'ItemsGroupCode' => 'integer',
            'ItemType' => ItemType::class,
            'QuantityOnStock' => 'decimal:4',
            'QuantityOrderedFromVendors' => 'decimal:4',
            'QuantityOrderedByCustomers' => 'decimal:4',
            'PurchaseItem' => 'boolean',
            'SalesItem' => 'boolean',
            'InventoryItem' => 'boolean',
            'Valid' => 'boolean',
        ];
    }

    /**
     * Get the default warehouse.
     */
    public function defaultWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'DefaultWarehouse', 'WarehouseCode');
    }

    /**
     * Scope: Active items.
     */
    public function scopeActive(QueryBuilder $query): QueryBuilder
    {
        return $query->where('Valid', 'tYES');
    }

    /**
     * Scope: Sales items.
     */
    public function scopeSales(QueryBuilder $query): QueryBuilder
    {
        return $query->where('SalesItem', 'tYES');
    }

    /**
     * Scope: Purchase items.
     */
    public function scopePurchase(QueryBuilder $query): QueryBuilder
    {
        return $query->where('PurchaseItem', 'tYES');
    }

    /**
     * Scope: Inventory items.
     */
    public function scopeInventory(QueryBuilder $query): QueryBuilder
    {
        return $query->where('InventoryItem', 'tYES');
    }

    /**
     * Scope: By group.
     */
    public function scopeByGroup(QueryBuilder $query, int $groupCode): QueryBuilder
    {
        return $query->where('ItemsGroupCode', $groupCode);
    }

    /**
     * Check if item has stock.
     */
    public function hasStock(): bool
    {
        return ($this->QuantityOnStock ?? 0) > 0;
    }

    /**
     * Get available quantity.
     */
    public function getAvailableQuantity(): float
    {
        $onStock = $this->QuantityOnStock ?? 0;
        $ordered = $this->QuantityOrderedByCustomers ?? 0;

        return max(0, $onStock - $ordered);
    }
}

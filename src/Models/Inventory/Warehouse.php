<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Models\Inventory;

use SapB1\Toolkit\Models\QueryBuilder;
use SapB1\Toolkit\Models\SapB1Model;

/**
 * Warehouse model.
 */
class Warehouse extends SapB1Model
{
    protected static string $entity = 'Warehouses';

    protected static string $primaryKey = 'WarehouseCode';

    protected array $fillable = [
        'WarehouseCode',
        'WarehouseName',
        'Street',
        'City',
        'ZipCode',
        'Country',
        'State',
        'Inactive',
        'DefaultBin',
        'BinActivat',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'Inactive' => 'boolean',
            'BinActivat' => 'boolean',
        ];
    }

    /**
     * Scope: Active warehouses.
     */
    public function scopeActive(QueryBuilder $query): QueryBuilder
    {
        return $query->where('Inactive', 'tNO');
    }

    /**
     * Scope: With bin locations.
     */
    public function scopeWithBins(QueryBuilder $query): QueryBuilder
    {
        return $query->where('BinActivat', 'tYES');
    }

    /**
     * Check if warehouse is active.
     */
    public function isActive(): bool
    {
        return ! ($this->Inactive ?? false);
    }

    /**
     * Check if warehouse uses bin locations.
     */
    public function usesBinLocations(): bool
    {
        return (bool) ($this->BinActivat ?? false);
    }
}

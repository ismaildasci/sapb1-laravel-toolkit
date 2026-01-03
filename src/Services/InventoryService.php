<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Services;

use SapB1\Toolkit\Actions\Inventory\StockTransferAction;
use SapB1\Toolkit\Builders\Inventory\StockTransferBuilder;
use SapB1\Toolkit\DTOs\Inventory\StockTransferDto;

/**
 * Service for inventory operations.
 */
final class InventoryService extends BaseService
{
    /**
     * Get stock level for an item.
     */
    public function getStockLevel(string $itemCode, ?string $warehouseCode = null): float
    {
        $item = $this->client()
            ->service('Items')
            ->find($itemCode);

        if ($warehouseCode === null) {
            return (float) ($item['QuantityOnStock'] ?? 0);
        }

        foreach ($item['ItemWarehouseInfoCollection'] ?? [] as $warehouse) {
            if ($warehouse['WarehouseCode'] === $warehouseCode) {
                return (float) ($warehouse['InStock'] ?? 0);
            }
        }

        return 0;
    }

    /**
     * Get stock levels for multiple items.
     *
     * @param  array<string>  $itemCodes
     * @return array<string, float>
     */
    public function getStockLevels(array $itemCodes, ?string $warehouseCode = null): array
    {
        $levels = [];

        foreach ($itemCodes as $itemCode) {
            $levels[$itemCode] = $this->getStockLevel($itemCode, $warehouseCode);
        }

        return $levels;
    }

    /**
     * Transfer stock between warehouses.
     */
    public function transferStock(
        string $itemCode,
        float $quantity,
        string $fromWarehouse,
        string $toWarehouse,
        ?string $comments = null
    ): StockTransferDto {
        $builder = StockTransferBuilder::create()
            ->docDate(date('Y-m-d'))
            ->fromWarehouse($fromWarehouse)
            ->toWarehouse($toWarehouse)
            ->addLine([
                'ItemCode' => $itemCode,
                'Quantity' => $quantity,
                'WarehouseCode' => $toWarehouse,
                'FromWarehouseCode' => $fromWarehouse,
            ]);

        if ($comments !== null) {
            $builder->comments($comments);
        }

        return (new StockTransferAction)
            ->connection($this->connection)
            ->create($builder);
    }

    /**
     * Bulk transfer multiple items.
     *
     * @param  array<array{itemCode: string, quantity: float}>  $items
     */
    public function bulkTransfer(
        array $items,
        string $fromWarehouse,
        string $toWarehouse,
        ?string $comments = null
    ): StockTransferDto {
        $builder = StockTransferBuilder::create()
            ->docDate(date('Y-m-d'))
            ->fromWarehouse($fromWarehouse)
            ->toWarehouse($toWarehouse);

        foreach ($items as $item) {
            $builder->addLine([
                'ItemCode' => $item['itemCode'],
                'Quantity' => $item['quantity'],
                'WarehouseCode' => $toWarehouse,
                'FromWarehouseCode' => $fromWarehouse,
            ]);
        }

        if ($comments !== null) {
            $builder->comments($comments);
        }

        return (new StockTransferAction)
            ->connection($this->connection)
            ->create($builder);
    }

    /**
     * Get items below reorder level.
     *
     * @return array<array<string, mixed>>
     */
    public function getItemsBelowReorderLevel(?string $warehouseCode = null): array
    {
        $filter = 'QuantityOnStock lt ReorderPoint and ReorderPoint gt 0';

        if ($warehouseCode !== null) {
            $filter .= " and ItemWarehouseInfoCollection/any(w: w/WarehouseCode eq '{$warehouseCode}')";
        }

        $response = $this->client()
            ->service('Items')
            ->queryBuilder()
            ->filter($filter)
            ->select(['ItemCode', 'ItemName', 'QuantityOnStock', 'ReorderPoint', 'ReorderQuantity'])
            ->get();

        return $response['value'] ?? [];
    }

    /**
     * Get warehouse stock summary.
     *
     * @return array<array<string, mixed>>
     */
    public function getWarehouseStockSummary(string $warehouseCode): array
    {
        $response = $this->client()
            ->service('Items')
            ->queryBuilder()
            ->filter("ItemWarehouseInfoCollection/any(w: w/WarehouseCode eq '{$warehouseCode}' and w/InStock gt 0)")
            ->select(['ItemCode', 'ItemName', 'ItemWarehouseInfoCollection'])
            ->get();

        $summary = [];

        foreach ($response['value'] ?? [] as $item) {
            foreach ($item['ItemWarehouseInfoCollection'] ?? [] as $warehouse) {
                if ($warehouse['WarehouseCode'] === $warehouseCode) {
                    $summary[] = [
                        'ItemCode' => $item['ItemCode'],
                        'ItemName' => $item['ItemName'],
                        'InStock' => $warehouse['InStock'] ?? 0,
                        'Committed' => $warehouse['Committed'] ?? 0,
                        'Ordered' => $warehouse['Ordered'] ?? 0,
                        'Available' => ($warehouse['InStock'] ?? 0) - ($warehouse['Committed'] ?? 0),
                    ];
                    break;
                }
            }
        }

        return $summary;
    }
}

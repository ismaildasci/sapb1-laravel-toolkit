<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Inventory;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class InventoryOpeningBalanceLineDto extends BaseDto
{
    public function __construct(
        public readonly ?int $lineNum = null,
        public readonly ?string $itemCode = null,
        public readonly ?string $itemDescription = null,
        public readonly ?float $quantity = null,
        public readonly ?float $price = null,
        public readonly ?float $total = null,
        public readonly ?string $warehouseCode = null,
        public readonly ?int $binEntry = null,
        public readonly ?string $batchNumber = null,
        public readonly ?string $serialNumber = null,
        public readonly ?string $currency = null,
        public readonly ?string $projectCode = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'lineNum' => isset($data['LineNum']) ? (int) $data['LineNum'] : null,
            'itemCode' => $data['ItemCode'] ?? null,
            'itemDescription' => $data['ItemDescription'] ?? null,
            'quantity' => isset($data['Quantity']) ? (float) $data['Quantity'] : null,
            'price' => isset($data['Price']) ? (float) $data['Price'] : null,
            'total' => isset($data['Total']) ? (float) $data['Total'] : null,
            'warehouseCode' => $data['WarehouseCode'] ?? null,
            'binEntry' => isset($data['BinEntry']) ? (int) $data['BinEntry'] : null,
            'batchNumber' => $data['BatchNumber'] ?? null,
            'serialNumber' => $data['SerialNumber'] ?? null,
            'currency' => $data['Currency'] ?? null,
            'projectCode' => $data['ProjectCode'] ?? null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'LineNum' => $this->lineNum,
            'ItemCode' => $this->itemCode,
            'ItemDescription' => $this->itemDescription,
            'Quantity' => $this->quantity,
            'Price' => $this->price,
            'Total' => $this->total,
            'WarehouseCode' => $this->warehouseCode,
            'BinEntry' => $this->binEntry,
            'BatchNumber' => $this->batchNumber,
            'SerialNumber' => $this->serialNumber,
            'Currency' => $this->currency,
            'ProjectCode' => $this->projectCode,
        ], fn ($value) => $value !== null);
    }
}

<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Inventory;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class InventoryTransferRequestLineDto extends BaseDto
{
    public function __construct(
        public readonly ?int $lineNum = null,
        public readonly ?string $itemCode = null,
        public readonly ?string $itemDescription = null,
        public readonly ?float $quantity = null,
        public readonly ?string $fromWarehouseCode = null,
        public readonly ?string $warehouseCode = null,
        public readonly ?int $fromBinEntry = null,
        public readonly ?int $binEntry = null,
        public readonly ?string $projectCode = null,
        public readonly ?string $serialNumber = null,
        public readonly ?string $batchNumber = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'lineNum' => isset($data['LineNum']) ? (int) $data['LineNum'] : null,
            'itemCode' => $data['ItemCode'] ?? null,
            'itemDescription' => $data['ItemDescription'] ?? null,
            'quantity' => isset($data['Quantity']) ? (float) $data['Quantity'] : null,
            'fromWarehouseCode' => $data['FromWarehouseCode'] ?? null,
            'warehouseCode' => $data['WarehouseCode'] ?? null,
            'fromBinEntry' => isset($data['FromBinEntry']) ? (int) $data['FromBinEntry'] : null,
            'binEntry' => isset($data['BinEntry']) ? (int) $data['BinEntry'] : null,
            'projectCode' => $data['ProjectCode'] ?? null,
            'serialNumber' => $data['SerialNumber'] ?? null,
            'batchNumber' => $data['BatchNumber'] ?? null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'LineNum' => $this->lineNum,
            'ItemCode' => $this->itemCode,
            'ItemDescription' => $this->itemDescription,
            'Quantity' => $this->quantity,
            'FromWarehouseCode' => $this->fromWarehouseCode,
            'WarehouseCode' => $this->warehouseCode,
            'FromBinEntry' => $this->fromBinEntry,
            'BinEntry' => $this->binEntry,
            'ProjectCode' => $this->projectCode,
            'SerialNumber' => $this->serialNumber,
            'BatchNumber' => $this->batchNumber,
        ], fn ($value) => $value !== null);
    }
}

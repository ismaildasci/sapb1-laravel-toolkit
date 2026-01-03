<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Inventory;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class InventoryPostingLineDto extends BaseDto
{
    public function __construct(
        public readonly ?int $lineNum = null,
        public readonly ?string $itemCode = null,
        public readonly ?string $itemDescription = null,
        public readonly ?float $countedQuantity = null,
        public readonly ?string $warehouseCode = null,
        public readonly ?int $binEntry = null,
        public readonly ?string $batchNumber = null,
        public readonly ?string $serialNumber = null,
        public readonly ?string $uoMCode = null,
        public readonly ?string $barCode = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'lineNum' => isset($data['LineNum']) ? (int) $data['LineNum'] : null,
            'itemCode' => $data['ItemCode'] ?? null,
            'itemDescription' => $data['ItemDescription'] ?? null,
            'countedQuantity' => isset($data['CountedQuantity']) ? (float) $data['CountedQuantity'] : null,
            'warehouseCode' => $data['WarehouseCode'] ?? null,
            'binEntry' => isset($data['BinEntry']) ? (int) $data['BinEntry'] : null,
            'batchNumber' => $data['BatchNumber'] ?? null,
            'serialNumber' => $data['SerialNumber'] ?? null,
            'uoMCode' => $data['UoMCode'] ?? null,
            'barCode' => $data['BarCode'] ?? null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'LineNum' => $this->lineNum,
            'ItemCode' => $this->itemCode,
            'ItemDescription' => $this->itemDescription,
            'CountedQuantity' => $this->countedQuantity,
            'WarehouseCode' => $this->warehouseCode,
            'BinEntry' => $this->binEntry,
            'BatchNumber' => $this->batchNumber,
            'SerialNumber' => $this->serialNumber,
            'UoMCode' => $this->uoMCode,
            'BarCode' => $this->barCode,
        ], fn ($value) => $value !== null);
    }
}

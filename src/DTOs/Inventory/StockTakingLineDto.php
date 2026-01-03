<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Inventory;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class StockTakingLineDto extends BaseDto
{
    public function __construct(
        public readonly ?int $lineNum = null,
        public readonly ?string $itemCode = null,
        public readonly ?string $itemDescription = null,
        public readonly ?float $countedQuantity = null,
        public readonly ?float $inWarehouseQuantity = null,
        public readonly ?string $warehouseCode = null,
        public readonly ?int $binEntry = null,
        public readonly ?string $batchNumber = null,
        public readonly ?string $serialNumber = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'lineNum' => isset($data['LineNum']) ? (int) $data['LineNum'] : null,
            'itemCode' => $data['ItemCode'] ?? null,
            'itemDescription' => $data['ItemDescription'] ?? null,
            'countedQuantity' => isset($data['CountedQuantity']) ? (float) $data['CountedQuantity'] : null,
            'inWarehouseQuantity' => isset($data['InWarehouseQuantity']) ? (float) $data['InWarehouseQuantity'] : null,
            'warehouseCode' => $data['WarehouseCode'] ?? null,
            'binEntry' => isset($data['BinEntry']) ? (int) $data['BinEntry'] : null,
            'batchNumber' => $data['BatchNumber'] ?? null,
            'serialNumber' => $data['SerialNumber'] ?? null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'LineNum' => $this->lineNum,
            'ItemCode' => $this->itemCode,
            'ItemDescription' => $this->itemDescription,
            'CountedQuantity' => $this->countedQuantity,
            'InWarehouseQuantity' => $this->inWarehouseQuantity,
            'WarehouseCode' => $this->warehouseCode,
            'BinEntry' => $this->binEntry,
            'BatchNumber' => $this->batchNumber,
            'SerialNumber' => $this->serialNumber,
        ], fn ($value) => $value !== null);
    }
}

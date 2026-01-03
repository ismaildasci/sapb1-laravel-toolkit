<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Inventory;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class PickListLineDto extends BaseDto
{
    public function __construct(
        public readonly ?int $lineNumber = null,
        public readonly ?string $orderEntry = null,
        public readonly ?int $orderRowId = null,
        public readonly ?string $itemCode = null,
        public readonly ?string $itemDescription = null,
        public readonly ?float $releasedQuantity = null,
        public readonly ?float $pickedQuantity = null,
        public readonly ?string $warehouseCode = null,
        public readonly ?int $binEntry = null,
        public readonly ?string $batchNumber = null,
        public readonly ?string $serialNumber = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'lineNumber' => isset($data['LineNumber']) ? (int) $data['LineNumber'] : null,
            'orderEntry' => $data['OrderEntry'] ?? null,
            'orderRowId' => isset($data['OrderRowID']) ? (int) $data['OrderRowID'] : null,
            'itemCode' => $data['ItemCode'] ?? null,
            'itemDescription' => $data['ItemDescription'] ?? null,
            'releasedQuantity' => isset($data['ReleasedQuantity']) ? (float) $data['ReleasedQuantity'] : null,
            'pickedQuantity' => isset($data['PickedQuantity']) ? (float) $data['PickedQuantity'] : null,
            'warehouseCode' => $data['WarehouseCode'] ?? null,
            'binEntry' => isset($data['BinEntry']) ? (int) $data['BinEntry'] : null,
            'batchNumber' => $data['BatchNumber'] ?? null,
            'serialNumber' => $data['SerialNumber'] ?? null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'LineNumber' => $this->lineNumber,
            'OrderEntry' => $this->orderEntry,
            'OrderRowID' => $this->orderRowId,
            'ItemCode' => $this->itemCode,
            'ItemDescription' => $this->itemDescription,
            'ReleasedQuantity' => $this->releasedQuantity,
            'PickedQuantity' => $this->pickedQuantity,
            'WarehouseCode' => $this->warehouseCode,
            'BinEntry' => $this->binEntry,
            'BatchNumber' => $this->batchNumber,
            'SerialNumber' => $this->serialNumber,
        ], fn ($value) => $value !== null);
    }
}

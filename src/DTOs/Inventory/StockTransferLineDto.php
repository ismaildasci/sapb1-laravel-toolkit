<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Inventory;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class StockTransferLineDto extends BaseDto
{
    public function __construct(
        public readonly ?string $itemCode = null,
        public readonly ?string $itemDescription = null,
        public readonly ?float $quantity = null,
        public readonly ?string $warehouseCode = null,
        public readonly ?string $fromWarehouseCode = null,
        public readonly ?int $lineNum = null,
        public readonly ?string $batchNumber = null,
        public readonly ?string $serialNumber = null,
        public readonly ?string $unitMeasure = null,
        public readonly ?float $unitsOfMeasurement = null,
        public readonly ?string $costingCode = null,
        public readonly ?string $costingCode2 = null,
        public readonly ?string $costingCode3 = null,
        public readonly ?string $projectCode = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'itemCode' => $data['ItemCode'] ?? null,
            'itemDescription' => $data['ItemDescription'] ?? null,
            'quantity' => isset($data['Quantity']) ? (float) $data['Quantity'] : null,
            'warehouseCode' => $data['WarehouseCode'] ?? null,
            'fromWarehouseCode' => $data['FromWarehouseCode'] ?? null,
            'lineNum' => isset($data['LineNum']) ? (int) $data['LineNum'] : null,
            'batchNumber' => $data['BatchNumber'] ?? null,
            'serialNumber' => $data['SerialNumber'] ?? null,
            'unitMeasure' => $data['UnitMeasure'] ?? null,
            'unitsOfMeasurement' => isset($data['UnitsOfMeasurement']) ? (float) $data['UnitsOfMeasurement'] : null,
            'costingCode' => $data['CostingCode'] ?? null,
            'costingCode2' => $data['CostingCode2'] ?? null,
            'costingCode3' => $data['CostingCode3'] ?? null,
            'projectCode' => $data['ProjectCode'] ?? null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'ItemCode' => $this->itemCode,
            'ItemDescription' => $this->itemDescription,
            'Quantity' => $this->quantity,
            'WarehouseCode' => $this->warehouseCode,
            'FromWarehouseCode' => $this->fromWarehouseCode,
            'LineNum' => $this->lineNum,
            'BatchNumber' => $this->batchNumber,
            'SerialNumber' => $this->serialNumber,
            'UnitMeasure' => $this->unitMeasure,
            'UnitsOfMeasurement' => $this->unitsOfMeasurement,
            'CostingCode' => $this->costingCode,
            'CostingCode2' => $this->costingCode2,
            'CostingCode3' => $this->costingCode3,
            'ProjectCode' => $this->projectCode,
        ], fn ($value) => $value !== null);
    }
}

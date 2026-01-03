<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Inventory;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * Inventory General Entry Line DTO.
 *
 * @phpstan-consistent-constructor
 */
final class InventoryGenEntryLineDto extends BaseDto
{
    public function __construct(
        public readonly ?int $lineNum = null,
        public readonly ?string $itemCode = null,
        public readonly ?string $itemDescription = null,
        public readonly ?float $quantity = null,
        public readonly ?float $price = null,
        public readonly ?string $currency = null,
        public readonly ?float $rate = null,
        public readonly ?string $warehouseCode = null,
        public readonly ?string $accountCode = null,
        public readonly ?string $costingCode = null,
        public readonly ?string $costingCode2 = null,
        public readonly ?string $costingCode3 = null,
        public readonly ?string $projectCode = null,
        public readonly ?string $uoMEntry = null,
        public readonly ?string $uoMCode = null,
        public readonly ?int $binEntry = null,
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
            'price' => isset($data['Price']) ? (float) $data['Price'] : null,
            'currency' => $data['Currency'] ?? null,
            'rate' => isset($data['Rate']) ? (float) $data['Rate'] : null,
            'warehouseCode' => $data['WarehouseCode'] ?? null,
            'accountCode' => $data['AccountCode'] ?? null,
            'costingCode' => $data['CostingCode'] ?? null,
            'costingCode2' => $data['CostingCode2'] ?? null,
            'costingCode3' => $data['CostingCode3'] ?? null,
            'projectCode' => $data['ProjectCode'] ?? null,
            'uoMEntry' => isset($data['UoMEntry']) ? (string) $data['UoMEntry'] : null,
            'uoMCode' => $data['UoMCode'] ?? null,
            'binEntry' => isset($data['BinEntry']) ? (int) $data['BinEntry'] : null,
            'serialNumber' => $data['SerialNumber'] ?? null,
            'batchNumber' => $data['BatchNumber'] ?? null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'LineNum' => $this->lineNum,
            'ItemCode' => $this->itemCode,
            'ItemDescription' => $this->itemDescription,
            'Quantity' => $this->quantity,
            'Price' => $this->price,
            'Currency' => $this->currency,
            'Rate' => $this->rate,
            'WarehouseCode' => $this->warehouseCode,
            'AccountCode' => $this->accountCode,
            'CostingCode' => $this->costingCode,
            'CostingCode2' => $this->costingCode2,
            'CostingCode3' => $this->costingCode3,
            'ProjectCode' => $this->projectCode,
            'UoMEntry' => $this->uoMEntry,
            'UoMCode' => $this->uoMCode,
            'BinEntry' => $this->binEntry,
            'SerialNumber' => $this->serialNumber,
            'BatchNumber' => $this->batchNumber,
        ], fn ($value) => $value !== null);
    }
}

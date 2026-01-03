<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class DocumentLineDto extends BaseDto
{
    public function __construct(
        public readonly ?string $itemCode = null,
        public readonly ?string $itemDescription = null,
        public readonly ?float $quantity = null,
        public readonly ?float $price = null,
        public readonly ?float $priceAfterVat = null,
        public readonly ?string $currency = null,
        public readonly ?float $rate = null,
        public readonly ?float $discountPercent = null,
        public readonly ?string $warehouseCode = null,
        public readonly ?string $accountCode = null,
        public readonly ?string $taxCode = null,
        public readonly ?float $taxTotal = null,
        public readonly ?float $lineTotal = null,
        public readonly ?float $grossTotal = null,
        public readonly ?int $lineNum = null,
        public readonly ?int $baseEntry = null,
        public readonly ?int $baseLine = null,
        public readonly ?int $baseType = null,
        public readonly ?string $batchNumber = null,
        public readonly ?string $serialNumber = null,
        public readonly ?string $unitMeasure = null,
        public readonly ?float $unitsOfMeasurement = null,
        public readonly ?string $costingCode = null,
        public readonly ?string $costingCode2 = null,
        public readonly ?string $costingCode3 = null,
        public readonly ?string $costingCode4 = null,
        public readonly ?string $costingCode5 = null,
        public readonly ?string $projectCode = null,
        public readonly ?string $freeText = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'itemCode' => $data['ItemCode'] ?? null,
            'itemDescription' => $data['ItemDescription'] ?? null,
            'quantity' => isset($data['Quantity']) ? (float) $data['Quantity'] : null,
            'price' => isset($data['Price']) ? (float) $data['Price'] : null,
            'priceAfterVat' => isset($data['PriceAfterVAT']) ? (float) $data['PriceAfterVAT'] : null,
            'currency' => $data['Currency'] ?? null,
            'rate' => isset($data['Rate']) ? (float) $data['Rate'] : null,
            'discountPercent' => isset($data['DiscountPercent']) ? (float) $data['DiscountPercent'] : null,
            'warehouseCode' => $data['WarehouseCode'] ?? null,
            'accountCode' => $data['AccountCode'] ?? null,
            'taxCode' => $data['TaxCode'] ?? null,
            'taxTotal' => isset($data['TaxTotal']) ? (float) $data['TaxTotal'] : null,
            'lineTotal' => isset($data['LineTotal']) ? (float) $data['LineTotal'] : null,
            'grossTotal' => isset($data['GrossTotal']) ? (float) $data['GrossTotal'] : null,
            'lineNum' => isset($data['LineNum']) ? (int) $data['LineNum'] : null,
            'baseEntry' => isset($data['BaseEntry']) ? (int) $data['BaseEntry'] : null,
            'baseLine' => isset($data['BaseLine']) ? (int) $data['BaseLine'] : null,
            'baseType' => isset($data['BaseType']) ? (int) $data['BaseType'] : null,
            'batchNumber' => $data['BatchNumber'] ?? null,
            'serialNumber' => $data['SerialNumber'] ?? null,
            'unitMeasure' => $data['UnitMeasure'] ?? null,
            'unitsOfMeasurement' => isset($data['UnitsOfMeasurement']) ? (float) $data['UnitsOfMeasurement'] : null,
            'costingCode' => $data['CostingCode'] ?? null,
            'costingCode2' => $data['CostingCode2'] ?? null,
            'costingCode3' => $data['CostingCode3'] ?? null,
            'costingCode4' => $data['CostingCode4'] ?? null,
            'costingCode5' => $data['CostingCode5'] ?? null,
            'projectCode' => $data['ProjectCode'] ?? null,
            'freeText' => $data['FreeText'] ?? null,
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
            'Price' => $this->price,
            'PriceAfterVAT' => $this->priceAfterVat,
            'Currency' => $this->currency,
            'Rate' => $this->rate,
            'DiscountPercent' => $this->discountPercent,
            'WarehouseCode' => $this->warehouseCode,
            'AccountCode' => $this->accountCode,
            'TaxCode' => $this->taxCode,
            'TaxTotal' => $this->taxTotal,
            'LineTotal' => $this->lineTotal,
            'GrossTotal' => $this->grossTotal,
            'LineNum' => $this->lineNum,
            'BaseEntry' => $this->baseEntry,
            'BaseLine' => $this->baseLine,
            'BaseType' => $this->baseType,
            'BatchNumber' => $this->batchNumber,
            'SerialNumber' => $this->serialNumber,
            'UnitMeasure' => $this->unitMeasure,
            'UnitsOfMeasurement' => $this->unitsOfMeasurement,
            'CostingCode' => $this->costingCode,
            'CostingCode2' => $this->costingCode2,
            'CostingCode3' => $this->costingCode3,
            'CostingCode4' => $this->costingCode4,
            'CostingCode5' => $this->costingCode5,
            'ProjectCode' => $this->projectCode,
            'FreeText' => $this->freeText,
        ], fn ($value) => $value !== null);
    }
}

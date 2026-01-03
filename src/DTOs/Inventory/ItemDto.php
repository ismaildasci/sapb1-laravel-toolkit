<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Inventory;

use SapB1\Toolkit\DTOs\Base\BaseDto;
use SapB1\Toolkit\Enums\BoYesNo;
use SapB1\Toolkit\Enums\ItemClass;
use SapB1\Toolkit\Enums\ItemType;
use SapB1\Toolkit\Enums\ValuationMethod;

/**
 * @phpstan-consistent-constructor
 */
final class ItemDto extends BaseDto
{
    public function __construct(
        public readonly ?string $itemCode = null,
        public readonly ?string $itemName = null,
        public readonly ?string $foreignName = null,
        public readonly ?ItemType $itemType = null,
        public readonly ?ItemClass $itemClass = null,
        public readonly ?string $itemsGroupCode = null,
        public readonly ?string $customsGroupCode = null,
        public readonly ?ValuationMethod $valuationMethod = null,
        public readonly ?string $defaultWarehouse = null,
        public readonly ?string $purchaseItem = null,
        public readonly ?string $salesItem = null,
        public readonly ?string $inventoryItem = null,
        public readonly ?float $quantityOnStock = null,
        public readonly ?float $quantityOrderedFromVendors = null,
        public readonly ?float $quantityOrderedByCustomers = null,
        public readonly ?string $manufacturer = null,
        public readonly ?BoYesNo $valid = null,
        public readonly ?BoYesNo $frozen = null,
        public readonly ?string $barCode = null,
        public readonly ?string $salesUnitOfMeasure = null,
        public readonly ?string $purchaseUnitOfMeasure = null,
        public readonly ?string $inventoryUoMEntry = null,
        public readonly ?string $defaultSalesUoMEntry = null,
        public readonly ?string $defaultPurchasingUoMEntry = null,
        public readonly ?BoYesNo $manageSerialNumbers = null,
        public readonly ?BoYesNo $manageBatchNumbers = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'itemCode' => $data['ItemCode'] ?? null,
            'itemName' => $data['ItemName'] ?? null,
            'foreignName' => $data['ForeignName'] ?? null,
            'itemType' => isset($data['ItemType']) ? ItemType::tryFrom($data['ItemType']) : null,
            'itemClass' => isset($data['ItemClass']) ? ItemClass::tryFrom($data['ItemClass']) : null,
            'itemsGroupCode' => isset($data['ItemsGroupCode']) ? (string) $data['ItemsGroupCode'] : null,
            'customsGroupCode' => isset($data['CustomsGroupCode']) ? (string) $data['CustomsGroupCode'] : null,
            'valuationMethod' => isset($data['ValuationMethod']) ? ValuationMethod::tryFrom($data['ValuationMethod']) : null,
            'defaultWarehouse' => $data['DefaultWarehouse'] ?? null,
            'purchaseItem' => $data['PurchaseItem'] ?? null,
            'salesItem' => $data['SalesItem'] ?? null,
            'inventoryItem' => $data['InventoryItem'] ?? null,
            'quantityOnStock' => isset($data['QuantityOnStock']) ? (float) $data['QuantityOnStock'] : null,
            'quantityOrderedFromVendors' => isset($data['QuantityOrderedFromVendors']) ? (float) $data['QuantityOrderedFromVendors'] : null,
            'quantityOrderedByCustomers' => isset($data['QuantityOrderedByCustomers']) ? (float) $data['QuantityOrderedByCustomers'] : null,
            'manufacturer' => isset($data['Manufacturer']) ? (string) $data['Manufacturer'] : null,
            'valid' => isset($data['Valid']) ? BoYesNo::tryFrom($data['Valid']) : null,
            'frozen' => isset($data['Frozen']) ? BoYesNo::tryFrom($data['Frozen']) : null,
            'barCode' => $data['BarCode'] ?? null,
            'salesUnitOfMeasure' => isset($data['SalesUnitOfMeasure']) ? (string) $data['SalesUnitOfMeasure'] : null,
            'purchaseUnitOfMeasure' => isset($data['PurchaseUnitOfMeasure']) ? (string) $data['PurchaseUnitOfMeasure'] : null,
            'inventoryUoMEntry' => isset($data['InventoryUoMEntry']) ? (string) $data['InventoryUoMEntry'] : null,
            'defaultSalesUoMEntry' => isset($data['DefaultSalesUoMEntry']) ? (string) $data['DefaultSalesUoMEntry'] : null,
            'defaultPurchasingUoMEntry' => isset($data['DefaultPurchasingUoMEntry']) ? (string) $data['DefaultPurchasingUoMEntry'] : null,
            'manageSerialNumbers' => isset($data['ManageSerialNumbers']) ? BoYesNo::tryFrom($data['ManageSerialNumbers']) : null,
            'manageBatchNumbers' => isset($data['ManageBatchNumbers']) ? BoYesNo::tryFrom($data['ManageBatchNumbers']) : null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'ItemCode' => $this->itemCode,
            'ItemName' => $this->itemName,
            'ForeignName' => $this->foreignName,
            'ItemType' => $this->itemType?->value,
            'ItemClass' => $this->itemClass?->value,
            'ItemsGroupCode' => $this->itemsGroupCode,
            'CustomsGroupCode' => $this->customsGroupCode,
            'ValuationMethod' => $this->valuationMethod?->value,
            'DefaultWarehouse' => $this->defaultWarehouse,
            'PurchaseItem' => $this->purchaseItem,
            'SalesItem' => $this->salesItem,
            'InventoryItem' => $this->inventoryItem,
            'QuantityOnStock' => $this->quantityOnStock,
            'QuantityOrderedFromVendors' => $this->quantityOrderedFromVendors,
            'QuantityOrderedByCustomers' => $this->quantityOrderedByCustomers,
            'Manufacturer' => $this->manufacturer,
            'Valid' => $this->valid?->value,
            'Frozen' => $this->frozen?->value,
            'BarCode' => $this->barCode,
            'SalesUnitOfMeasure' => $this->salesUnitOfMeasure,
            'PurchaseUnitOfMeasure' => $this->purchaseUnitOfMeasure,
            'InventoryUoMEntry' => $this->inventoryUoMEntry,
            'DefaultSalesUoMEntry' => $this->defaultSalesUoMEntry,
            'DefaultPurchasingUoMEntry' => $this->defaultPurchasingUoMEntry,
            'ManageSerialNumbers' => $this->manageSerialNumbers?->value,
            'ManageBatchNumbers' => $this->manageBatchNumbers?->value,
        ], fn ($value) => $value !== null);
    }
}

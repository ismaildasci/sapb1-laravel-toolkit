<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Inventory;

use SapB1\Toolkit\Builders\BaseBuilder;
use SapB1\Toolkit\Enums\BoYesNo;
use SapB1\Toolkit\Enums\ItemClass;
use SapB1\Toolkit\Enums\ItemType;
use SapB1\Toolkit\Enums\ValuationMethod;

/**
 * Builder for Items.
 *
 * @phpstan-consistent-constructor
 */
final class ItemBuilder extends BaseBuilder
{
    public function itemCode(string $code): static
    {
        return $this->set('ItemCode', $code);
    }

    public function itemName(string $name): static
    {
        return $this->set('ItemName', $name);
    }

    public function foreignName(string $name): static
    {
        return $this->set('ForeignName', $name);
    }

    public function itemType(ItemType $type): static
    {
        return $this->set('ItemType', $type->value);
    }

    public function itemClass(ItemClass $class): static
    {
        return $this->set('ItemClass', $class->value);
    }

    public function itemsGroupCode(int $code): static
    {
        return $this->set('ItemsGroupCode', $code);
    }

    public function valuationMethod(ValuationMethod $method): static
    {
        return $this->set('ValuationMethod', $method->value);
    }

    public function defaultWarehouse(string $warehouse): static
    {
        return $this->set('DefaultWarehouse', $warehouse);
    }

    public function purchaseItem(BoYesNo $value): static
    {
        return $this->set('PurchaseItem', $value->value);
    }

    public function salesItem(BoYesNo $value): static
    {
        return $this->set('SalesItem', $value->value);
    }

    public function inventoryItem(BoYesNo $value): static
    {
        return $this->set('InventoryItem', $value->value);
    }

    public function barCode(string $barCode): static
    {
        return $this->set('BarCode', $barCode);
    }

    public function manufacturer(int $manufacturer): static
    {
        return $this->set('Manufacturer', $manufacturer);
    }

    public function valid(BoYesNo $value): static
    {
        return $this->set('Valid', $value->value);
    }

    public function frozen(BoYesNo $value): static
    {
        return $this->set('Frozen', $value->value);
    }

    public function manageSerialNumbers(BoYesNo $value): static
    {
        return $this->set('ManageSerialNumbers', $value->value);
    }

    public function manageBatchNumbers(BoYesNo $value): static
    {
        return $this->set('ManageBatchNumbers', $value->value);
    }

    /**
     * @return array<string, mixed>
     */
    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Inventory;

use SapB1\Toolkit\Builders\BaseBuilder;
use SapB1\Toolkit\Enums\BoYesNo;

/**
 * Builder for Bin Locations.
 *
 * @phpstan-consistent-constructor
 */
final class BinLocationBuilder extends BaseBuilder
{
    public function binCode(string $code): static
    {
        return $this->set('BinCode', $code);
    }

    public function warehouseCode(string $code): static
    {
        return $this->set('Warehouse', $code);
    }

    public function sublevelOne(int $code): static
    {
        return $this->set('SL1Code', $code);
    }

    public function sublevelTwo(int $code): static
    {
        return $this->set('SL2Code', $code);
    }

    public function sublevelThree(int $code): static
    {
        return $this->set('SL3Code', $code);
    }

    public function sublevelFour(int $code): static
    {
        return $this->set('SL4Code', $code);
    }

    public function minimumQty(float $qty): static
    {
        return $this->set('MinimumQty', $qty);
    }

    public function maximumQty(float $qty): static
    {
        return $this->set('MaximumQty', $qty);
    }

    public function inactive(BoYesNo $value): static
    {
        return $this->set('Inactive', $value->value);
    }

    public function description(string $description): static
    {
        return $this->set('Description', $description);
    }

    public function alternativeSortCode(string $code): static
    {
        return $this->set('AlternativeSortCode', $code);
    }

    public function barCode(string $code): static
    {
        return $this->set('BarCode', $code);
    }

    public function attribute1(int $value): static
    {
        return $this->set('Attr1Val', $value);
    }

    public function attribute2(int $value): static
    {
        return $this->set('Attr2Val', $value);
    }

    public function attribute3(int $value): static
    {
        return $this->set('Attr3Val', $value);
    }

    public function attribute4(int $value): static
    {
        return $this->set('Attr4Val', $value);
    }

    public function attribute5(int $value): static
    {
        return $this->set('Attr5Val', $value);
    }

    public function attribute6(int $value): static
    {
        return $this->set('Attr6Val', $value);
    }

    public function attribute7(int $value): static
    {
        return $this->set('Attr7Val', $value);
    }

    public function attribute8(int $value): static
    {
        return $this->set('Attr8Val', $value);
    }

    public function attribute9(int $value): static
    {
        return $this->set('Attr9Val', $value);
    }

    public function attribute10(int $value): static
    {
        return $this->set('Attr10Val', $value);
    }

    public function specificItem(string $itemCode): static
    {
        return $this->set('SpecificItem', $itemCode);
    }

    public function specificItemGroup(int $group): static
    {
        return $this->set('SpecificItemGroup', $group);
    }

    public function restrictedItemType(BoYesNo $value): static
    {
        return $this->set('RestrictedItemType', $value->value);
    }

    public function batchRestrictions(int $value): static
    {
        return $this->set('BatchRestrictions', $value);
    }

    public function restrictedTransType(BoYesNo $value): static
    {
        return $this->set('RestrictedTransType', $value->value);
    }

    public function restrictionReason(string $reason): static
    {
        return $this->set('RestrictionReason', $reason);
    }

    public function maximumWeight(float $weight): static
    {
        return $this->set('MaximumWeight', $weight);
    }

    public function maximumWeightUnit(float $unit): static
    {
        return $this->set('MaximumWeightUnit', $unit);
    }

    public function receivingBinLocation(BoYesNo $value): static
    {
        return $this->set('ReceivingBinLocation', $value->value);
    }

    public function excludeAutoAllocOnIssue(BoYesNo $value): static
    {
        return $this->set('ExcludeAutoAllocOnIssue', $value->value);
    }

    /**
     * @return array<string, mixed>
     */
    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Inventory;

use SapB1\Toolkit\Builders\BaseBuilder;
use SapB1\Toolkit\Enums\BoYesNo;

/**
 * Builder for Warehouses.
 *
 * @phpstan-consistent-constructor
 */
final class WarehouseBuilder extends BaseBuilder
{
    public function warehouseCode(string $code): static
    {
        return $this->set('WarehouseCode', $code);
    }

    public function warehouseName(string $name): static
    {
        return $this->set('WarehouseName', $name);
    }

    public function street(string $street): static
    {
        return $this->set('Street', $street);
    }

    public function block(string $block): static
    {
        return $this->set('Block', $block);
    }

    public function zipCode(string $zipCode): static
    {
        return $this->set('ZipCode', $zipCode);
    }

    public function city(string $city): static
    {
        return $this->set('City', $city);
    }

    public function county(string $county): static
    {
        return $this->set('County', $county);
    }

    public function country(string $country): static
    {
        return $this->set('Country', $country);
    }

    public function state(string $state): static
    {
        return $this->set('State', $state);
    }

    public function inactive(BoYesNo $value): static
    {
        return $this->set('Inactive', $value->value);
    }

    public function branchCode(int $code): static
    {
        return $this->set('BranchCode', $code);
    }

    /**
     * @return array<string, mixed>
     */
    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

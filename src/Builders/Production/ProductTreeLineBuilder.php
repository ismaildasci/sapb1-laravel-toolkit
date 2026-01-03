<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Production;

use SapB1\Toolkit\Builders\BaseBuilder;
use SapB1\Toolkit\Enums\IssueMethod;

/**
 * @phpstan-consistent-constructor
 */
final class ProductTreeLineBuilder extends BaseBuilder
{
    public function itemCode(string $code): static
    {
        return $this->set('ItemCode', $code);
    }

    public function quantity(float $quantity): static
    {
        return $this->set('Quantity', $quantity);
    }

    public function warehouse(string $warehouse): static
    {
        return $this->set('Warehouse', $warehouse);
    }

    public function priceList(int $priceList): static
    {
        return $this->set('PriceList', $priceList);
    }

    public function issueMethod(IssueMethod $method): static
    {
        return $this->set('IssueMethod', $method->value);
    }

    public function itemDescription(string $description): static
    {
        return $this->set('ItemDescription', $description);
    }

    public function additionalQuantity(float $quantity): static
    {
        return $this->set('AdditionalQuantity', $quantity);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

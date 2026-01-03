<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Admin;

use SapB1\Toolkit\Builders\BaseBuilder;

/**
 * @phpstan-consistent-constructor
 */
final class SpecialPriceBuilder extends BaseBuilder
{
    public function itemCode(string $itemCode): static
    {
        return $this->set('ItemCode', $itemCode);
    }

    public function cardCode(string $cardCode): static
    {
        return $this->set('CardCode', $cardCode);
    }

    public function price(float $price): static
    {
        return $this->set('Price', $price);
    }

    public function currency(string $currency): static
    {
        return $this->set('Currency', $currency);
    }

    public function discountPercent(float $percent): static
    {
        return $this->set('DiscountPercent', $percent);
    }

    public function priceListNum(int $listNum): static
    {
        return $this->set('PriceListNum', $listNum);
    }

    public function validFrom(string $date): static
    {
        return $this->set('ValidFrom', $date);
    }

    public function validTo(string $date): static
    {
        return $this->set('ValidTo', $date);
    }

    public function autoUpdate(int $autoUpdate): static
    {
        return $this->set('AutoUpdate', $autoUpdate);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

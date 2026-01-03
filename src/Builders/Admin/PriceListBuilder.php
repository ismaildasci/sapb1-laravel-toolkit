<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Admin;

use SapB1\Toolkit\Builders\BaseBuilder;
use SapB1\Toolkit\Enums\BoYesNo;

/**
 * @phpstan-consistent-constructor
 */
final class PriceListBuilder extends BaseBuilder
{
    public function priceListName(string $name): static
    {
        return $this->set('PriceListName', $name);
    }

    public function isGrossPrice(BoYesNo $isGross): static
    {
        return $this->set('IsGrossPrice', $isGross->value);
    }

    public function active(BoYesNo $active): static
    {
        return $this->set('Active', $active->value);
    }

    public function validFrom(string $date): static
    {
        return $this->set('ValidFrom', $date);
    }

    public function validTo(string $date): static
    {
        return $this->set('ValidTo', $date);
    }

    public function defaultPrimeCurrency(string $currency): static
    {
        return $this->set('DefaultPrimeCurrency', $currency);
    }

    public function defaultAdditionalCurrency1(string $currency): static
    {
        return $this->set('DefaultAdditionalCurrency1', $currency);
    }

    public function defaultAdditionalCurrency2(string $currency): static
    {
        return $this->set('DefaultAdditionalCurrency2', $currency);
    }

    public function roundingRule(int $rule): static
    {
        return $this->set('RoundingRule', $rule);
    }

    public function basePriceList(int $listNo): static
    {
        return $this->set('BasePriceList', $listNo);
    }

    public function factor(float $factor): static
    {
        return $this->set('Factor', $factor);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

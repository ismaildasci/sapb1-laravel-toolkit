<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Finance;

use SapB1\Toolkit\Builders\BaseBuilder;

/**
 * @phpstan-consistent-constructor
 */
final class WithholdingTaxCodeBuilder extends BaseBuilder
{
    public function wTCode(string $code): static
    {
        return $this->set('WTCode', $code);
    }

    public function wTName(string $name): static
    {
        return $this->set('WTName', $name);
    }

    public function officialCode(string $code): static
    {
        return $this->set('OfficialCode', $code);
    }

    public function category(string $category): static
    {
        return $this->set('Category', $category);
    }

    public function baseType(string $type): static
    {
        return $this->set('BaseType', $type);
    }

    public function baseAmount(float $amount): static
    {
        return $this->set('BaseAmount', $amount);
    }

    public function account(int $account): static
    {
        return $this->set('Account', $account);
    }

    public function withholdingRate(float $rate): static
    {
        return $this->set('WithholdingRate', $rate);
    }

    public function effectiveDateFrom(string $date): static
    {
        return $this->set('EffectiveDateFrom', $date);
    }

    public function effectiveDateTo(string $date): static
    {
        return $this->set('EffectiveDateTo', $date);
    }

    public function inactive(string $value): static
    {
        return $this->set('Inactive', $value);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

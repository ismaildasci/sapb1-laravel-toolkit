<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Finance;

use SapB1\Toolkit\Builders\BaseBuilder;

/**
 * @phpstan-consistent-constructor
 */
final class SalesTaxCodeBuilder extends BaseBuilder
{
    public function code(string $code): static
    {
        return $this->set('Code', $code);
    }

    public function name(string $name): static
    {
        return $this->set('Name', $name);
    }

    public function userSignature(string $signature): static
    {
        return $this->set('UserSignature', $signature);
    }

    public function rate(string $rate): static
    {
        return $this->set('Rate', $rate);
    }

    public function effective(string $effective): static
    {
        return $this->set('Effective', $effective);
    }

    public function taxableAmount(string $amount): static
    {
        return $this->set('TaxableAmount', $amount);
    }

    public function isActive(string $active): static
    {
        return $this->set('IsActive', $active);
    }

    public function taxType(string $type): static
    {
        return $this->set('TaxType', $type);
    }

    public function freight(string $freight): static
    {
        return $this->set('Freight', $freight);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

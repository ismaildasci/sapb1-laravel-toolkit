<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Finance;

use SapB1\Toolkit\Builders\BaseBuilder;

/**
 * @phpstan-consistent-constructor
 */
final class VatGroupBuilder extends BaseBuilder
{
    public function code(string $code): static
    {
        return $this->set('Code', $code);
    }

    public function name(string $name): static
    {
        return $this->set('Name', $name);
    }

    public function category(string $category): static
    {
        return $this->set('Category', $category);
    }

    public function taxAccount(float $account): static
    {
        return $this->set('TaxAccount', $account);
    }

    public function euPurchaseAccount(string $account): static
    {
        return $this->set('EUPurchaseAccount', $account);
    }

    public function euSalesAccount(string $account): static
    {
        return $this->set('EUSalesAccount', $account);
    }

    public function inactive(string $inactive): static
    {
        return $this->set('Inactive', $inactive);
    }

    public function vatPercent(float $percent): static
    {
        return $this->set('VatPercent', $percent);
    }

    public function reportingCode(string $code): static
    {
        return $this->set('ReportingCode', $code);
    }

    public function nonDeductiblePercent(string $percent): static
    {
        return $this->set('NonDeductiblePercent', $percent);
    }

    public function isDefault(string $value): static
    {
        return $this->set('IsDefault', $value);
    }

    public function deferredTaxAcc(string $account): static
    {
        return $this->set('DeferredTaxAcc', $account);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

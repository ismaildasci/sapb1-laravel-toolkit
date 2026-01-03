<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Finance;

use SapB1\Toolkit\Builders\BaseBuilder;

/**
 * @phpstan-consistent-constructor
 */
final class SalesTaxAuthorityBuilder extends BaseBuilder
{
    public function name(string $name): static
    {
        return $this->set('Name', $name);
    }

    public function userSignature(string $signature): static
    {
        return $this->set('UserSignature', $signature);
    }

    public function taxAccount(string $account): static
    {
        return $this->set('TaxAccount', $account);
    }

    public function taxType(string $type): static
    {
        return $this->set('TaxType', $type);
    }

    public function taxDefinition(string $definition): static
    {
        return $this->set('TaxDefinition', $definition);
    }

    public function businessPartner(string $partner): static
    {
        return $this->set('BusinessPartner', $partner);
    }

    public function dataExportCode(string $code): static
    {
        return $this->set('DataExportCode', $code);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

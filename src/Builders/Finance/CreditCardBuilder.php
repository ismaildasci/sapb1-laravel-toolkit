<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Finance;

use SapB1\Toolkit\Builders\BaseBuilder;

/**
 * @phpstan-consistent-constructor
 */
final class CreditCardBuilder extends BaseBuilder
{
    public function creditCardName(string $name): static
    {
        return $this->set('CreditCardName', $name);
    }

    public function gLAccount(string $account): static
    {
        return $this->set('GLAccount', $account);
    }

    public function telephone(string $telephone): static
    {
        return $this->set('Telephone', $telephone);
    }

    public function companyId(string $id): static
    {
        return $this->set('CompanyId', $id);
    }

    public function countryCode(string $code): static
    {
        return $this->set('CountryCode', $code);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

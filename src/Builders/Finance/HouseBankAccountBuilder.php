<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Finance;

use SapB1\Toolkit\Builders\BaseBuilder;

/**
 * @phpstan-consistent-constructor
 */
final class HouseBankAccountBuilder extends BaseBuilder
{
    public function bankCode(string $code): static
    {
        return $this->set('BankCode', $code);
    }

    public function accNo(string $accNo): static
    {
        return $this->set('AccNo', $accNo);
    }

    public function branch(string $branch): static
    {
        return $this->set('Branch', $branch);
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

    public function accountName(string $name): static
    {
        return $this->set('AccountName', $name);
    }

    public function controlKey(string $key): static
    {
        return $this->set('ControlKey', $key);
    }

    public function glAccount(string $account): static
    {
        return $this->set('GLAccount', $account);
    }

    public function glInterimAccount(string $account): static
    {
        return $this->set('GLInterimAccount', $account);
    }

    public function ibanOfHouseBankAccount(string $iban): static
    {
        return $this->set('IbanOfHouseBankAccount', $iban);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

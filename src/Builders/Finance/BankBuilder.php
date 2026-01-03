<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Finance;

use SapB1\Toolkit\Builders\BaseBuilder;

/**
 * @phpstan-consistent-constructor
 */
final class BankBuilder extends BaseBuilder
{
    public function bankCode(string $code): static
    {
        return $this->set('BankCode', $code);
    }

    public function bankName(string $name): static
    {
        return $this->set('BankName', $name);
    }

    public function accountforOutgoingChecks(string $account): static
    {
        return $this->set('AccountforOutgoingChecks', $account);
    }

    public function branchforOutgoingChecks(string $branch): static
    {
        return $this->set('BranchforOutgoingChecks', $branch);
    }

    public function nextCheckNumber(string $number): static
    {
        return $this->set('NextCheckNumber', $number);
    }

    public function swiftNo(string $swift): static
    {
        return $this->set('SwiftNo', $swift);
    }

    public function iSCN(string $iscn): static
    {
        return $this->set('ISCN', $iscn);
    }

    public function countryCode(string $code): static
    {
        return $this->set('CountryCode', $code);
    }

    public function postOffice(string $value): static
    {
        return $this->set('PostOffice', $value);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

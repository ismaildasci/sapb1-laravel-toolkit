<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Finance;

use SapB1\Toolkit\Builders\BaseBuilder;

/**
 * @phpstan-consistent-constructor
 */
final class ChecksforPaymentBuilder extends BaseBuilder
{
    public function checkNumber(int $number): static
    {
        return $this->set('CheckNumber', $number);
    }

    public function bankCode(string $code): static
    {
        return $this->set('BankCode', $code);
    }

    public function branch(string $branch): static
    {
        return $this->set('Branch', $branch);
    }

    public function bankName(string $name): static
    {
        return $this->set('BankName', $name);
    }

    public function checkDate(string $date): static
    {
        return $this->set('CheckDate', $date);
    }

    public function accountNumber(string $number): static
    {
        return $this->set('AccountNumber', $number);
    }

    public function details(string $details): static
    {
        return $this->set('Details', $details);
    }

    public function journalRemarks(string $remarks): static
    {
        return $this->set('JournalRemarks', $remarks);
    }

    public function paymentDate(string $date): static
    {
        return $this->set('PaymentDate', $date);
    }

    public function checkAmount(float $amount): static
    {
        return $this->set('CheckAmount', $amount);
    }

    public function vendorCode(string $code): static
    {
        return $this->set('VendorCode', $code);
    }

    public function checkCurrency(string $currency): static
    {
        return $this->set('CheckCurrency', $currency);
    }

    public function vendorName(string $name): static
    {
        return $this->set('VendorName', $name);
    }

    public function customerAccountCode(string $code): static
    {
        return $this->set('CustomerAccountCode', $code);
    }

    public function address(string $address): static
    {
        return $this->set('Address', $address);
    }

    public function addressName(string $name): static
    {
        return $this->set('AddressName', $name);
    }

    public function manualCheck(string $value): static
    {
        return $this->set('ManualCheck', $value);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

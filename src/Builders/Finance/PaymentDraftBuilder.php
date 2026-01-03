<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Finance;

use SapB1\Toolkit\Builders\BaseBuilder;

/**
 * @phpstan-consistent-constructor
 */
final class PaymentDraftBuilder extends BaseBuilder
{
    public function docType(string $type): static
    {
        return $this->set('DocType', $type);
    }

    public function docDate(string $date): static
    {
        return $this->set('DocDate', $date);
    }

    public function cardCode(string $code): static
    {
        return $this->set('CardCode', $code);
    }

    public function cardName(string $name): static
    {
        return $this->set('CardName', $name);
    }

    public function address(string $address): static
    {
        return $this->set('Address', $address);
    }

    public function cashSum(float $sum): static
    {
        return $this->set('CashSum', $sum);
    }

    public function checkSum(float $sum): static
    {
        return $this->set('CheckSum', $sum);
    }

    public function transferSum(float $sum): static
    {
        return $this->set('TransferSum', $sum);
    }

    public function docCurrency(string $currency): static
    {
        return $this->set('DocCurrency', $currency);
    }

    public function reference1(string $reference): static
    {
        return $this->set('Reference1', $reference);
    }

    public function reference2(string $reference): static
    {
        return $this->set('Reference2', $reference);
    }

    public function remarks(string $remarks): static
    {
        return $this->set('Remarks', $remarks);
    }

    public function journalRemarks(string $remarks): static
    {
        return $this->set('JournalRemarks', $remarks);
    }

    public function taxDate(string $date): static
    {
        return $this->set('TaxDate', $date);
    }

    public function series(int $series): static
    {
        return $this->set('Series', $series);
    }

    public function project(string $project): static
    {
        return $this->set('Project', $project);
    }

    public function dueDate(string $date): static
    {
        return $this->set('DueDate', $date);
    }

    public function cashAccount(string $account): static
    {
        return $this->set('CashAccount', $account);
    }

    public function transferAccount(string $account): static
    {
        return $this->set('TransferAccount', $account);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

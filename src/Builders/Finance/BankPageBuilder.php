<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Finance;

use SapB1\Toolkit\Builders\BaseBuilder;

/**
 * @phpstan-consistent-constructor
 */
final class BankPageBuilder extends BaseBuilder
{
    public function accountCode(int $code): static
    {
        return $this->set('AccountCode', $code);
    }

    public function accountName(string $name): static
    {
        return $this->set('AccountName', $name);
    }

    public function reference(string $reference): static
    {
        return $this->set('Reference', $reference);
    }

    public function dueDate(string $date): static
    {
        return $this->set('DueDate', $date);
    }

    public function memo(string $memo): static
    {
        return $this->set('Memo', $memo);
    }

    public function debitAmount(float $amount): static
    {
        return $this->set('DebitAmount', $amount);
    }

    public function creditAmount(float $amount): static
    {
        return $this->set('CreditAmount', $amount);
    }

    public function bankMatch(string $match): static
    {
        return $this->set('BankMatch', $match);
    }

    public function cardCode(string $code): static
    {
        return $this->set('CardCode', $code);
    }

    public function cardName(string $name): static
    {
        return $this->set('CardName', $name);
    }

    public function statementNumber(string $number): static
    {
        return $this->set('StatementNumber', $number);
    }

    public function invoiceNumber(string $number): static
    {
        return $this->set('InvoiceNumber', $number);
    }

    public function paymentReference(string $reference): static
    {
        return $this->set('PaymentReference', $reference);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

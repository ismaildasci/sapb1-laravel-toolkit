<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Finance;

use SapB1\Toolkit\Builders\BaseBuilder;
use SapB1\Toolkit\DTOs\Finance\PaymentInvoiceDto;

/**
 * Builder for Incoming/Outgoing Payments.
 *
 * @phpstan-consistent-constructor
 */
final class PaymentBuilder extends BaseBuilder
{
    public function docType(string $type): static
    {
        return $this->set('DocType', $type);
    }

    public function cardCode(string $cardCode): static
    {
        return $this->set('CardCode', $cardCode);
    }

    public function cardName(string $cardName): static
    {
        return $this->set('CardName', $cardName);
    }

    public function docDate(string $date): static
    {
        return $this->set('DocDate', $date);
    }

    public function dueDate(string $date): static
    {
        return $this->set('DueDate', $date);
    }

    public function taxDate(string $date): static
    {
        return $this->set('TaxDate', $date);
    }

    public function docCurrency(string $currency): static
    {
        return $this->set('DocCurrency', $currency);
    }

    public function docRate(float $rate): static
    {
        return $this->set('DocRate', $rate);
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

    public function transferAccount(string $account): static
    {
        return $this->set('TransferAccount', $account);
    }

    public function transferSum(float $sum): static
    {
        return $this->set('TransferSum', $sum);
    }

    public function transferDate(string $date): static
    {
        return $this->set('TransferDate', $date);
    }

    public function transferReference(string $reference): static
    {
        return $this->set('TransferReference', $reference);
    }

    public function checkAccount(string $account): static
    {
        return $this->set('CheckAccount', $account);
    }

    public function checkSum(float $sum): static
    {
        return $this->set('CheckSum', $sum);
    }

    public function cashAccount(string $account): static
    {
        return $this->set('CashAccount', $account);
    }

    public function cashSum(float $sum): static
    {
        return $this->set('CashSum', $sum);
    }

    public function series(int $series): static
    {
        return $this->set('Series', $series);
    }

    /**
     * @param  array<PaymentInvoiceDto|array<string, mixed>>  $invoices
     */
    public function paymentInvoices(array $invoices): static
    {
        $mappedInvoices = array_map(
            fn ($invoice) => $invoice instanceof PaymentInvoiceDto ? $invoice->toArray() : $invoice,
            $invoices
        );

        return $this->set('PaymentInvoices', $mappedInvoices);
    }

    /**
     * @param  PaymentInvoiceDto|array<string, mixed>  $invoice
     */
    public function addInvoice(PaymentInvoiceDto|array $invoice): static
    {
        $invoices = $this->get('PaymentInvoices', []);
        $invoices[] = $invoice instanceof PaymentInvoiceDto ? $invoice->toArray() : $invoice;

        return $this->set('PaymentInvoices', $invoices);
    }

    /**
     * @return array<string, mixed>
     */
    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Finance;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class PaymentDto extends BaseDto
{
    /**
     * @param  array<PaymentInvoiceDto>  $paymentInvoices
     */
    public function __construct(
        public readonly ?int $docEntry = null,
        public readonly ?int $docNum = null,
        public readonly ?string $docType = null,
        public readonly ?string $cardCode = null,
        public readonly ?string $cardName = null,
        public readonly ?string $docDate = null,
        public readonly ?string $dueDate = null,
        public readonly ?string $taxDate = null,
        public readonly ?float $docTotal = null,
        public readonly ?float $docTotalFc = null,
        public readonly ?string $docCurrency = null,
        public readonly ?float $docRate = null,
        public readonly ?string $reference1 = null,
        public readonly ?string $reference2 = null,
        public readonly ?string $remarks = null,
        public readonly ?string $journalRemarks = null,
        public readonly ?string $paymentType = null,
        public readonly ?string $transferAccount = null,
        public readonly ?float $transferSum = null,
        public readonly ?string $transferDate = null,
        public readonly ?string $transferReference = null,
        public readonly ?string $checkAccount = null,
        public readonly ?float $checkSum = null,
        public readonly ?string $cashAccount = null,
        public readonly ?float $cashSum = null,
        public readonly ?int $series = null,
        public readonly ?string $countryHouse = null,
        public readonly ?int $handWritten = null,
        public readonly array $paymentInvoices = [],
    ) {}

    protected static function mapFromArray(array $data): array
    {
        $invoices = [];
        if (isset($data['PaymentInvoices']) && is_array($data['PaymentInvoices'])) {
            foreach ($data['PaymentInvoices'] as $invoice) {
                $invoices[] = PaymentInvoiceDto::fromArray($invoice);
            }
        }

        return [
            'docEntry' => isset($data['DocEntry']) ? (int) $data['DocEntry'] : null,
            'docNum' => isset($data['DocNum']) ? (int) $data['DocNum'] : null,
            'docType' => $data['DocType'] ?? null,
            'cardCode' => $data['CardCode'] ?? null,
            'cardName' => $data['CardName'] ?? null,
            'docDate' => $data['DocDate'] ?? null,
            'dueDate' => $data['DueDate'] ?? null,
            'taxDate' => $data['TaxDate'] ?? null,
            'docTotal' => isset($data['DocTotal']) ? (float) $data['DocTotal'] : null,
            'docTotalFc' => isset($data['DocTotalFc']) ? (float) $data['DocTotalFc'] : null,
            'docCurrency' => $data['DocCurrency'] ?? null,
            'docRate' => isset($data['DocRate']) ? (float) $data['DocRate'] : null,
            'reference1' => $data['Reference1'] ?? null,
            'reference2' => $data['Reference2'] ?? null,
            'remarks' => $data['Remarks'] ?? null,
            'journalRemarks' => $data['JournalRemarks'] ?? null,
            'paymentType' => $data['PaymentType'] ?? null,
            'transferAccount' => $data['TransferAccount'] ?? null,
            'transferSum' => isset($data['TransferSum']) ? (float) $data['TransferSum'] : null,
            'transferDate' => $data['TransferDate'] ?? null,
            'transferReference' => $data['TransferReference'] ?? null,
            'checkAccount' => $data['CheckAccount'] ?? null,
            'checkSum' => isset($data['CheckSum']) ? (float) $data['CheckSum'] : null,
            'cashAccount' => $data['CashAccount'] ?? null,
            'cashSum' => isset($data['CashSum']) ? (float) $data['CashSum'] : null,
            'series' => isset($data['Series']) ? (int) $data['Series'] : null,
            'countryHouse' => $data['CounterReference'] ?? null,
            'handWritten' => isset($data['HandWritten']) ? (int) $data['HandWritten'] : null,
            'paymentInvoices' => $invoices,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = array_filter([
            'DocEntry' => $this->docEntry,
            'DocNum' => $this->docNum,
            'DocType' => $this->docType,
            'CardCode' => $this->cardCode,
            'CardName' => $this->cardName,
            'DocDate' => $this->docDate,
            'DueDate' => $this->dueDate,
            'TaxDate' => $this->taxDate,
            'DocTotal' => $this->docTotal,
            'DocTotalFc' => $this->docTotalFc,
            'DocCurrency' => $this->docCurrency,
            'DocRate' => $this->docRate,
            'Reference1' => $this->reference1,
            'Reference2' => $this->reference2,
            'Remarks' => $this->remarks,
            'JournalRemarks' => $this->journalRemarks,
            'PaymentType' => $this->paymentType,
            'TransferAccount' => $this->transferAccount,
            'TransferSum' => $this->transferSum,
            'TransferDate' => $this->transferDate,
            'TransferReference' => $this->transferReference,
            'CheckAccount' => $this->checkAccount,
            'CheckSum' => $this->checkSum,
            'CashAccount' => $this->cashAccount,
            'CashSum' => $this->cashSum,
            'Series' => $this->series,
            'CounterReference' => $this->countryHouse,
            'HandWritten' => $this->handWritten,
        ], fn ($value) => $value !== null);

        if (! empty($this->paymentInvoices)) {
            $data['PaymentInvoices'] = array_map(
                fn (PaymentInvoiceDto $invoice) => $invoice->toArray(),
                $this->paymentInvoices
            );
        }

        return $data;
    }
}

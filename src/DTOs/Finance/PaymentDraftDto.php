<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Finance;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class PaymentDraftDto extends BaseDto
{
    public function __construct(
        public readonly ?int $docEntry = null,
        public readonly ?int $docNum = null,
        public readonly ?string $docType = null,
        public readonly ?string $docDate = null,
        public readonly ?string $cardCode = null,
        public readonly ?string $cardName = null,
        public readonly ?string $address = null,
        public readonly ?float $cashSum = null,
        public readonly ?float $checkSum = null,
        public readonly ?float $transferSum = null,
        public readonly ?float $docTotal = null,
        public readonly ?float $docTotalFC = null,
        public readonly ?float $docTotalSys = null,
        public readonly ?string $docCurrency = null,
        public readonly ?string $reference1 = null,
        public readonly ?string $reference2 = null,
        public readonly ?string $remarks = null,
        public readonly ?string $journalRemarks = null,
        public readonly ?string $status = null,
        public readonly ?string $taxDate = null,
        public readonly ?int $series = null,
        public readonly ?string $project = null,
        public readonly ?string $dueDate = null,
        public readonly ?string $handWritten = null,
        public readonly ?int $transactionCode = null,
        public readonly ?string $counterReference = null,
        public readonly ?string $recurring = null,
        public readonly ?string $contactPersonCode = null,
        public readonly ?string $cashAccount = null,
        public readonly ?string $transferAccount = null,
        public readonly ?string $primaryFormItem = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'docEntry' => isset($data['DocEntry']) ? (int) $data['DocEntry'] : null,
            'docNum' => isset($data['DocNum']) ? (int) $data['DocNum'] : null,
            'docType' => $data['DocType'] ?? null,
            'docDate' => $data['DocDate'] ?? null,
            'cardCode' => $data['CardCode'] ?? null,
            'cardName' => $data['CardName'] ?? null,
            'address' => $data['Address'] ?? null,
            'cashSum' => isset($data['CashSum']) ? (float) $data['CashSum'] : null,
            'checkSum' => isset($data['CheckSum']) ? (float) $data['CheckSum'] : null,
            'transferSum' => isset($data['TransferSum']) ? (float) $data['TransferSum'] : null,
            'docTotal' => isset($data['DocTotal']) ? (float) $data['DocTotal'] : null,
            'docTotalFC' => isset($data['DocTotalFC']) ? (float) $data['DocTotalFC'] : null,
            'docTotalSys' => isset($data['DocTotalSys']) ? (float) $data['DocTotalSys'] : null,
            'docCurrency' => $data['DocCurrency'] ?? null,
            'reference1' => $data['Reference1'] ?? null,
            'reference2' => $data['Reference2'] ?? null,
            'remarks' => $data['Remarks'] ?? null,
            'journalRemarks' => $data['JournalRemarks'] ?? null,
            'status' => $data['Status'] ?? null,
            'taxDate' => $data['TaxDate'] ?? null,
            'series' => isset($data['Series']) ? (int) $data['Series'] : null,
            'project' => $data['Project'] ?? null,
            'dueDate' => $data['DueDate'] ?? null,
            'handWritten' => $data['HandWritten'] ?? null,
            'transactionCode' => isset($data['TransactionCode']) ? (int) $data['TransactionCode'] : null,
            'counterReference' => $data['CounterReference'] ?? null,
            'recurring' => $data['Recurring'] ?? null,
            'contactPersonCode' => $data['ContactPersonCode'] ?? null,
            'cashAccount' => $data['CashAccount'] ?? null,
            'transferAccount' => $data['TransferAccount'] ?? null,
            'primaryFormItem' => $data['PrimaryFormItem'] ?? null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'DocEntry' => $this->docEntry,
            'DocNum' => $this->docNum,
            'DocType' => $this->docType,
            'DocDate' => $this->docDate,
            'CardCode' => $this->cardCode,
            'CardName' => $this->cardName,
            'Address' => $this->address,
            'CashSum' => $this->cashSum,
            'CheckSum' => $this->checkSum,
            'TransferSum' => $this->transferSum,
            'DocTotal' => $this->docTotal,
            'DocTotalFC' => $this->docTotalFC,
            'DocTotalSys' => $this->docTotalSys,
            'DocCurrency' => $this->docCurrency,
            'Reference1' => $this->reference1,
            'Reference2' => $this->reference2,
            'Remarks' => $this->remarks,
            'JournalRemarks' => $this->journalRemarks,
            'Status' => $this->status,
            'TaxDate' => $this->taxDate,
            'Series' => $this->series,
            'Project' => $this->project,
            'DueDate' => $this->dueDate,
            'HandWritten' => $this->handWritten,
            'TransactionCode' => $this->transactionCode,
            'CounterReference' => $this->counterReference,
            'Recurring' => $this->recurring,
            'ContactPersonCode' => $this->contactPersonCode,
            'CashAccount' => $this->cashAccount,
            'TransferAccount' => $this->transferAccount,
            'PrimaryFormItem' => $this->primaryFormItem,
        ], fn ($value) => $value !== null);
    }
}

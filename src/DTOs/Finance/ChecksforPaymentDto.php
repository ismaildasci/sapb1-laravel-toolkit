<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Finance;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class ChecksforPaymentDto extends BaseDto
{
    public function __construct(
        public readonly ?int $checkKey = null,
        public readonly ?int $checkNumber = null,
        public readonly ?string $bankCode = null,
        public readonly ?string $branch = null,
        public readonly ?string $bankName = null,
        public readonly ?string $checkDate = null,
        public readonly ?string $accountNumber = null,
        public readonly ?string $details = null,
        public readonly ?string $journalRemarks = null,
        public readonly ?string $paymentDate = null,
        public readonly ?int $paymentNo = null,
        public readonly ?float $checkAmount = null,
        public readonly ?string $transferable = null,
        public readonly ?string $vendorCode = null,
        public readonly ?string $checkCurrency = null,
        public readonly ?string $canceled = null,
        public readonly ?string $cardOrAccount = null,
        public readonly ?string $printed = null,
        public readonly ?string $vendorName = null,
        public readonly ?string $signature = null,
        public readonly ?string $customerAccountCode = null,
        public readonly ?int $transactionNumber = null,
        public readonly ?string $address = null,
        public readonly ?string $createJournalEntry = null,
        public readonly ?string $updateDate = null,
        public readonly ?string $creationDate = null,
        public readonly ?string $taxTotal = null,
        public readonly ?string $taxDate = null,
        public readonly ?string $deductionRefundAmount = null,
        public readonly ?string $printedBy = null,
        public readonly ?int $countryCode = null,
        public readonly ?float $totalInWords = null,
        public readonly ?string $addressName = null,
        public readonly ?string $manualCheck = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'checkKey' => isset($data['CheckKey']) ? (int) $data['CheckKey'] : null,
            'checkNumber' => isset($data['CheckNumber']) ? (int) $data['CheckNumber'] : null,
            'bankCode' => $data['BankCode'] ?? null,
            'branch' => $data['Branch'] ?? null,
            'bankName' => $data['BankName'] ?? null,
            'checkDate' => $data['CheckDate'] ?? null,
            'accountNumber' => $data['AccountNumber'] ?? null,
            'details' => $data['Details'] ?? null,
            'journalRemarks' => $data['JournalRemarks'] ?? null,
            'paymentDate' => $data['PaymentDate'] ?? null,
            'paymentNo' => isset($data['PaymentNo']) ? (int) $data['PaymentNo'] : null,
            'checkAmount' => isset($data['CheckAmount']) ? (float) $data['CheckAmount'] : null,
            'transferable' => $data['Transferable'] ?? null,
            'vendorCode' => $data['VendorCode'] ?? null,
            'checkCurrency' => $data['CheckCurrency'] ?? null,
            'canceled' => $data['Canceled'] ?? null,
            'cardOrAccount' => $data['CardOrAccount'] ?? null,
            'printed' => $data['Printed'] ?? null,
            'vendorName' => $data['VendorName'] ?? null,
            'signature' => $data['Signature'] ?? null,
            'customerAccountCode' => $data['CustomerAccountCode'] ?? null,
            'transactionNumber' => isset($data['TransactionNumber']) ? (int) $data['TransactionNumber'] : null,
            'address' => $data['Address'] ?? null,
            'createJournalEntry' => $data['CreateJournalEntry'] ?? null,
            'updateDate' => $data['UpdateDate'] ?? null,
            'creationDate' => $data['CreationDate'] ?? null,
            'taxTotal' => $data['TaxTotal'] ?? null,
            'taxDate' => $data['TaxDate'] ?? null,
            'deductionRefundAmount' => $data['DeductionRefundAmount'] ?? null,
            'printedBy' => $data['PrintedBy'] ?? null,
            'countryCode' => isset($data['CountryCode']) ? (int) $data['CountryCode'] : null,
            'totalInWords' => isset($data['TotalInWords']) ? (float) $data['TotalInWords'] : null,
            'addressName' => $data['AddressName'] ?? null,
            'manualCheck' => $data['ManualCheck'] ?? null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'CheckKey' => $this->checkKey,
            'CheckNumber' => $this->checkNumber,
            'BankCode' => $this->bankCode,
            'Branch' => $this->branch,
            'BankName' => $this->bankName,
            'CheckDate' => $this->checkDate,
            'AccountNumber' => $this->accountNumber,
            'Details' => $this->details,
            'JournalRemarks' => $this->journalRemarks,
            'PaymentDate' => $this->paymentDate,
            'PaymentNo' => $this->paymentNo,
            'CheckAmount' => $this->checkAmount,
            'Transferable' => $this->transferable,
            'VendorCode' => $this->vendorCode,
            'CheckCurrency' => $this->checkCurrency,
            'Canceled' => $this->canceled,
            'CardOrAccount' => $this->cardOrAccount,
            'Printed' => $this->printed,
            'VendorName' => $this->vendorName,
            'Signature' => $this->signature,
            'CustomerAccountCode' => $this->customerAccountCode,
            'TransactionNumber' => $this->transactionNumber,
            'Address' => $this->address,
            'CreateJournalEntry' => $this->createJournalEntry,
            'UpdateDate' => $this->updateDate,
            'CreationDate' => $this->creationDate,
            'TaxTotal' => $this->taxTotal,
            'TaxDate' => $this->taxDate,
            'DeductionRefundAmount' => $this->deductionRefundAmount,
            'PrintedBy' => $this->printedBy,
            'CountryCode' => $this->countryCode,
            'TotalInWords' => $this->totalInWords,
            'AddressName' => $this->addressName,
            'ManualCheck' => $this->manualCheck,
        ], fn ($value) => $value !== null);
    }
}

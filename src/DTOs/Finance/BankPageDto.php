<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Finance;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class BankPageDto extends BaseDto
{
    public function __construct(
        public readonly ?int $accountCode = null,
        public readonly ?int $sequence = null,
        public readonly ?string $accountName = null,
        public readonly ?string $reference = null,
        public readonly ?string $dueDate = null,
        public readonly ?string $memo = null,
        public readonly ?float $debitAmount = null,
        public readonly ?float $creditAmount = null,
        public readonly ?string $bankMatch = null,
        public readonly ?string $dataSource = null,
        public readonly ?string $userSignature = null,
        public readonly ?string $externalCode = null,
        public readonly ?string $cardCode = null,
        public readonly ?string $cardName = null,
        public readonly ?string $statementNumber = null,
        public readonly ?string $invoiceNumber = null,
        public readonly ?string $paymentCreated = null,
        public readonly ?string $visualOrder = null,
        public readonly ?int $docNumberType = null,
        public readonly ?string $paymentReference = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'accountCode' => isset($data['AccountCode']) ? (int) $data['AccountCode'] : null,
            'sequence' => isset($data['Sequence']) ? (int) $data['Sequence'] : null,
            'accountName' => $data['AccountName'] ?? null,
            'reference' => $data['Reference'] ?? null,
            'dueDate' => $data['DueDate'] ?? null,
            'memo' => $data['Memo'] ?? null,
            'debitAmount' => isset($data['DebitAmount']) ? (float) $data['DebitAmount'] : null,
            'creditAmount' => isset($data['CreditAmount']) ? (float) $data['CreditAmount'] : null,
            'bankMatch' => $data['BankMatch'] ?? null,
            'dataSource' => $data['DataSource'] ?? null,
            'userSignature' => $data['UserSignature'] ?? null,
            'externalCode' => $data['ExternalCode'] ?? null,
            'cardCode' => $data['CardCode'] ?? null,
            'cardName' => $data['CardName'] ?? null,
            'statementNumber' => $data['StatementNumber'] ?? null,
            'invoiceNumber' => $data['InvoiceNumber'] ?? null,
            'paymentCreated' => $data['PaymentCreated'] ?? null,
            'visualOrder' => $data['VisualOrder'] ?? null,
            'docNumberType' => isset($data['DocNumberType']) ? (int) $data['DocNumberType'] : null,
            'paymentReference' => $data['PaymentReference'] ?? null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'AccountCode' => $this->accountCode,
            'Sequence' => $this->sequence,
            'AccountName' => $this->accountName,
            'Reference' => $this->reference,
            'DueDate' => $this->dueDate,
            'Memo' => $this->memo,
            'DebitAmount' => $this->debitAmount,
            'CreditAmount' => $this->creditAmount,
            'BankMatch' => $this->bankMatch,
            'DataSource' => $this->dataSource,
            'UserSignature' => $this->userSignature,
            'ExternalCode' => $this->externalCode,
            'CardCode' => $this->cardCode,
            'CardName' => $this->cardName,
            'StatementNumber' => $this->statementNumber,
            'InvoiceNumber' => $this->invoiceNumber,
            'PaymentCreated' => $this->paymentCreated,
            'VisualOrder' => $this->visualOrder,
            'DocNumberType' => $this->docNumberType,
            'PaymentReference' => $this->paymentReference,
        ], fn ($value) => $value !== null);
    }
}

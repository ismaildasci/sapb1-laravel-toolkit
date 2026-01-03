<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Finance;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class BankStatementRowDto extends BaseDto
{
    public function __construct(
        public readonly ?int $externalCode = null,
        public readonly ?string $accountNumber = null,
        public readonly ?string $sequenceNo = null,
        public readonly ?string $accountName = null,
        public readonly ?string $reference = null,
        public readonly ?float $debitAmountFC = null,
        public readonly ?float $creditAmountFC = null,
        public readonly ?float $debitAmountLC = null,
        public readonly ?float $creditAmountLC = null,
        public readonly ?string $creditCurrency = null,
        public readonly ?string $debitCurrency = null,
        public readonly ?string $dueDate = null,
        public readonly ?string $valueDate = null,
        public readonly ?string $details = null,
        public readonly ?string $visualOrder = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'externalCode' => isset($data['ExternalCode']) ? (int) $data['ExternalCode'] : null,
            'accountNumber' => $data['AccountNumber'] ?? null,
            'sequenceNo' => $data['SequenceNo'] ?? null,
            'accountName' => $data['AccountName'] ?? null,
            'reference' => $data['Reference'] ?? null,
            'debitAmountFC' => isset($data['DebitAmountFC']) ? (float) $data['DebitAmountFC'] : null,
            'creditAmountFC' => isset($data['CreditAmountFC']) ? (float) $data['CreditAmountFC'] : null,
            'debitAmountLC' => isset($data['DebitAmountLC']) ? (float) $data['DebitAmountLC'] : null,
            'creditAmountLC' => isset($data['CreditAmountLC']) ? (float) $data['CreditAmountLC'] : null,
            'creditCurrency' => $data['CreditCurrency'] ?? null,
            'debitCurrency' => $data['DebitCurrency'] ?? null,
            'dueDate' => $data['DueDate'] ?? null,
            'valueDate' => $data['ValueDate'] ?? null,
            'details' => $data['Details'] ?? null,
            'visualOrder' => $data['VisualOrder'] ?? null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'ExternalCode' => $this->externalCode,
            'AccountNumber' => $this->accountNumber,
            'SequenceNo' => $this->sequenceNo,
            'AccountName' => $this->accountName,
            'Reference' => $this->reference,
            'DebitAmountFC' => $this->debitAmountFC,
            'CreditAmountFC' => $this->creditAmountFC,
            'DebitAmountLC' => $this->debitAmountLC,
            'CreditAmountLC' => $this->creditAmountLC,
            'CreditCurrency' => $this->creditCurrency,
            'DebitCurrency' => $this->debitCurrency,
            'DueDate' => $this->dueDate,
            'ValueDate' => $this->valueDate,
            'Details' => $this->details,
            'VisualOrder' => $this->visualOrder,
        ], fn ($value) => $value !== null);
    }
}

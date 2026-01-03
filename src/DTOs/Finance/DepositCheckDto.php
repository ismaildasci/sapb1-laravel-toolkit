<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Finance;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class DepositCheckDto extends BaseDto
{
    public function __construct(
        public readonly ?int $lineNumber = null,
        public readonly ?string $checkKey = null,
        public readonly ?int $checkNumber = null,
        public readonly ?string $dueDate = null,
        public readonly ?string $currency = null,
        public readonly ?float $amount = null,
        public readonly ?float $amountLC = null,
        public readonly ?string $checkOwner = null,
        public readonly ?string $bankCode = null,
        public readonly ?string $branch = null,
        public readonly ?string $checkAccount = null,
        public readonly ?string $trnsfblAccount = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'lineNumber' => isset($data['LineNumber']) ? (int) $data['LineNumber'] : null,
            'checkKey' => $data['CheckKey'] ?? null,
            'checkNumber' => isset($data['CheckNumber']) ? (int) $data['CheckNumber'] : null,
            'dueDate' => $data['DueDate'] ?? null,
            'currency' => $data['Currency'] ?? null,
            'amount' => isset($data['Amount']) ? (float) $data['Amount'] : null,
            'amountLC' => isset($data['AmountLC']) ? (float) $data['AmountLC'] : null,
            'checkOwner' => $data['CheckOwner'] ?? null,
            'bankCode' => $data['BankCode'] ?? null,
            'branch' => $data['Branch'] ?? null,
            'checkAccount' => $data['CheckAccount'] ?? null,
            'trnsfblAccount' => $data['TrnsfblAccount'] ?? null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'LineNumber' => $this->lineNumber,
            'CheckKey' => $this->checkKey,
            'CheckNumber' => $this->checkNumber,
            'DueDate' => $this->dueDate,
            'Currency' => $this->currency,
            'Amount' => $this->amount,
            'AmountLC' => $this->amountLC,
            'CheckOwner' => $this->checkOwner,
            'BankCode' => $this->bankCode,
            'Branch' => $this->branch,
            'CheckAccount' => $this->checkAccount,
            'TrnsfblAccount' => $this->trnsfblAccount,
        ], fn ($value) => $value !== null);
    }
}

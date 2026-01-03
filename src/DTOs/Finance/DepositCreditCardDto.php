<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Finance;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class DepositCreditCardDto extends BaseDto
{
    public function __construct(
        public readonly ?int $lineNumber = null,
        public readonly ?string $creditCardCode = null,
        public readonly ?string $creditCardName = null,
        public readonly ?string $voucherNumber = null,
        public readonly ?string $dueDate = null,
        public readonly ?string $currency = null,
        public readonly ?float $amount = null,
        public readonly ?float $amountLC = null,
        public readonly ?string $creditAccount = null,
        public readonly ?string $cardNumber = null,
        public readonly ?string $holdersName = null,
        public readonly ?int $numOfPayments = null,
        public readonly ?float $firstPayment = null,
        public readonly ?float $additionalPayment = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'lineNumber' => isset($data['LineNumber']) ? (int) $data['LineNumber'] : null,
            'creditCardCode' => $data['CreditCardCode'] ?? null,
            'creditCardName' => $data['CreditCardName'] ?? null,
            'voucherNumber' => $data['VoucherNumber'] ?? null,
            'dueDate' => $data['DueDate'] ?? null,
            'currency' => $data['Currency'] ?? null,
            'amount' => isset($data['Amount']) ? (float) $data['Amount'] : null,
            'amountLC' => isset($data['AmountLC']) ? (float) $data['AmountLC'] : null,
            'creditAccount' => $data['CreditAccount'] ?? null,
            'cardNumber' => $data['CardNumber'] ?? null,
            'holdersName' => $data['HoldersName'] ?? null,
            'numOfPayments' => isset($data['NumOfPayments']) ? (int) $data['NumOfPayments'] : null,
            'firstPayment' => isset($data['FirstPayment']) ? (float) $data['FirstPayment'] : null,
            'additionalPayment' => isset($data['AdditionalPayment']) ? (float) $data['AdditionalPayment'] : null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'LineNumber' => $this->lineNumber,
            'CreditCardCode' => $this->creditCardCode,
            'CreditCardName' => $this->creditCardName,
            'VoucherNumber' => $this->voucherNumber,
            'DueDate' => $this->dueDate,
            'Currency' => $this->currency,
            'Amount' => $this->amount,
            'AmountLC' => $this->amountLC,
            'CreditAccount' => $this->creditAccount,
            'CardNumber' => $this->cardNumber,
            'HoldersName' => $this->holdersName,
            'NumOfPayments' => $this->numOfPayments,
            'FirstPayment' => $this->firstPayment,
            'AdditionalPayment' => $this->additionalPayment,
        ], fn ($value) => $value !== null);
    }
}

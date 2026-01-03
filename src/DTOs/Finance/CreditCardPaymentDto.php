<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Finance;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class CreditCardPaymentDto extends BaseDto
{
    public function __construct(
        public readonly ?int $dueDateCode = null,
        public readonly ?string $dueDateName = null,
        public readonly ?int $dueOn = null,
        public readonly ?int $dueFirstDay = null,
        public readonly ?int $paymentFirstDayOfMonth = null,
        public readonly ?int $numDaysAfterDueDateCode = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'dueDateCode' => isset($data['DueDateCode']) ? (int) $data['DueDateCode'] : null,
            'dueDateName' => $data['DueDateName'] ?? null,
            'dueOn' => isset($data['DueOn']) ? (int) $data['DueOn'] : null,
            'dueFirstDay' => isset($data['DueFirstDay']) ? (int) $data['DueFirstDay'] : null,
            'paymentFirstDayOfMonth' => isset($data['PaymentFirstDayOfMonth']) ? (int) $data['PaymentFirstDayOfMonth'] : null,
            'numDaysAfterDueDateCode' => isset($data['NumDaysAfterDueDateCode']) ? (int) $data['NumDaysAfterDueDateCode'] : null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'DueDateCode' => $this->dueDateCode,
            'DueDateName' => $this->dueDateName,
            'DueOn' => $this->dueOn,
            'DueFirstDay' => $this->dueFirstDay,
            'PaymentFirstDayOfMonth' => $this->paymentFirstDayOfMonth,
            'NumDaysAfterDueDateCode' => $this->numDaysAfterDueDateCode,
        ], fn ($value) => $value !== null);
    }
}

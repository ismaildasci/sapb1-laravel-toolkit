<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Finance;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class CashDiscountDto extends BaseDto
{
    public function __construct(
        public readonly ?string $code = null,
        public readonly ?string $name = null,
        public readonly ?int $byDate = null,
        public readonly ?string $freight = null,
        public readonly ?string $tax = null,
        public readonly ?float $discountPercent = null,
        public readonly ?int $numOfDays = null,
        public readonly ?int $numOfMonths = null,
        public readonly ?int $dayOfMonth = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'code' => $data['Code'] ?? null,
            'name' => $data['Name'] ?? null,
            'byDate' => isset($data['ByDate']) ? (int) $data['ByDate'] : null,
            'freight' => $data['Freight'] ?? null,
            'tax' => $data['Tax'] ?? null,
            'discountPercent' => isset($data['DiscountPercent']) ? (float) $data['DiscountPercent'] : null,
            'numOfDays' => isset($data['NumOfDays']) ? (int) $data['NumOfDays'] : null,
            'numOfMonths' => isset($data['NumOfMonths']) ? (int) $data['NumOfMonths'] : null,
            'dayOfMonth' => isset($data['DayOfMonth']) ? (int) $data['DayOfMonth'] : null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'Code' => $this->code,
            'Name' => $this->name,
            'ByDate' => $this->byDate,
            'Freight' => $this->freight,
            'Tax' => $this->tax,
            'DiscountPercent' => $this->discountPercent,
            'NumOfDays' => $this->numOfDays,
            'NumOfMonths' => $this->numOfMonths,
            'DayOfMonth' => $this->dayOfMonth,
        ], fn ($value) => $value !== null);
    }
}

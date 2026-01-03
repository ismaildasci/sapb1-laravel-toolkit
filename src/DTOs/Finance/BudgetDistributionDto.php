<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Finance;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class BudgetDistributionDto extends BaseDto
{
    public function __construct(
        public readonly ?int $divisionCode = null,
        public readonly ?string $description = null,
        public readonly ?float $january = null,
        public readonly ?float $february = null,
        public readonly ?float $march = null,
        public readonly ?float $april = null,
        public readonly ?float $may = null,
        public readonly ?float $june = null,
        public readonly ?float $july = null,
        public readonly ?float $august = null,
        public readonly ?float $september = null,
        public readonly ?float $october = null,
        public readonly ?float $november = null,
        public readonly ?float $december = null,
        public readonly ?float $total = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'divisionCode' => isset($data['DivisionCode']) ? (int) $data['DivisionCode'] : null,
            'description' => $data['Description'] ?? null,
            'january' => isset($data['January']) ? (float) $data['January'] : null,
            'february' => isset($data['February']) ? (float) $data['February'] : null,
            'march' => isset($data['March']) ? (float) $data['March'] : null,
            'april' => isset($data['April']) ? (float) $data['April'] : null,
            'may' => isset($data['May']) ? (float) $data['May'] : null,
            'june' => isset($data['June']) ? (float) $data['June'] : null,
            'july' => isset($data['July']) ? (float) $data['July'] : null,
            'august' => isset($data['August']) ? (float) $data['August'] : null,
            'september' => isset($data['September']) ? (float) $data['September'] : null,
            'october' => isset($data['October']) ? (float) $data['October'] : null,
            'november' => isset($data['November']) ? (float) $data['November'] : null,
            'december' => isset($data['December']) ? (float) $data['December'] : null,
            'total' => isset($data['Total']) ? (float) $data['Total'] : null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'DivisionCode' => $this->divisionCode,
            'Description' => $this->description,
            'January' => $this->january,
            'February' => $this->february,
            'March' => $this->march,
            'April' => $this->april,
            'May' => $this->may,
            'June' => $this->june,
            'July' => $this->july,
            'August' => $this->august,
            'September' => $this->september,
            'October' => $this->october,
            'November' => $this->november,
            'December' => $this->december,
            'Total' => $this->total,
        ], fn ($value) => $value !== null);
    }
}

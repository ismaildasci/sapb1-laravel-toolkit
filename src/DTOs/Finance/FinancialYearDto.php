<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Finance;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class FinancialYearDto extends BaseDto
{
    public function __construct(
        public readonly ?int $absEntry = null,
        public readonly ?string $code = null,
        public readonly ?string $description = null,
        public readonly ?string $startDate = null,
        public readonly ?string $endDate = null,
        public readonly ?int $assessYear = null,
        public readonly ?int $assessYearStart = null,
        public readonly ?int $assessYearEnd = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'absEntry' => isset($data['AbsEntry']) ? (int) $data['AbsEntry'] : null,
            'code' => $data['Code'] ?? null,
            'description' => $data['Description'] ?? null,
            'startDate' => $data['StartDate'] ?? null,
            'endDate' => $data['EndDate'] ?? null,
            'assessYear' => isset($data['AssessYear']) ? (int) $data['AssessYear'] : null,
            'assessYearStart' => isset($data['AssessYearStart']) ? (int) $data['AssessYearStart'] : null,
            'assessYearEnd' => isset($data['AssessYearEnd']) ? (int) $data['AssessYearEnd'] : null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'AbsEntry' => $this->absEntry,
            'Code' => $this->code,
            'Description' => $this->description,
            'StartDate' => $this->startDate,
            'EndDate' => $this->endDate,
            'AssessYear' => $this->assessYear,
            'AssessYearStart' => $this->assessYearStart,
            'AssessYearEnd' => $this->assessYearEnd,
        ], fn ($value) => $value !== null);
    }
}

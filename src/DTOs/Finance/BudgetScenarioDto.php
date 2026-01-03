<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Finance;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class BudgetScenarioDto extends BaseDto
{
    public function __construct(
        public readonly ?int $numerator = null,
        public readonly ?string $name = null,
        public readonly ?string $initialRatioPercentage = null,
        public readonly ?string $startofFiscalYear = null,
        public readonly ?int $basicBudget = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'numerator' => isset($data['Numerator']) ? (int) $data['Numerator'] : null,
            'name' => $data['Name'] ?? null,
            'initialRatioPercentage' => $data['InitialRatioPercentage'] ?? null,
            'startofFiscalYear' => $data['StartofFiscalYear'] ?? null,
            'basicBudget' => isset($data['BasicBudget']) ? (int) $data['BasicBudget'] : null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'Numerator' => $this->numerator,
            'Name' => $this->name,
            'InitialRatioPercentage' => $this->initialRatioPercentage,
            'StartofFiscalYear' => $this->startofFiscalYear,
            'BasicBudget' => $this->basicBudget,
        ], fn ($value) => $value !== null);
    }
}

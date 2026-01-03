<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Finance;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class BudgetLineDto extends BaseDto
{
    public function __construct(
        public readonly ?int $budgetKey = null,
        public readonly ?string $accountCode = null,
        public readonly ?int $budgetScenario = null,
        public readonly ?string $description = null,
        public readonly ?float $budgetAmount = null,
        public readonly ?string $budgetStatus = null,
        public readonly ?string $fiscalYear = null,
        public readonly ?int $precentOfAnnualBudget = null,
        public readonly ?string $startOfFiscalYear = null,
        public readonly ?int $budgetDistributionMethod = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'budgetKey' => isset($data['BudgetKey']) ? (int) $data['BudgetKey'] : null,
            'accountCode' => $data['AccountCode'] ?? null,
            'budgetScenario' => isset($data['BudgetScenario']) ? (int) $data['BudgetScenario'] : null,
            'description' => $data['Description'] ?? null,
            'budgetAmount' => isset($data['BudgetAmount']) ? (float) $data['BudgetAmount'] : null,
            'budgetStatus' => $data['BudgetStatus'] ?? null,
            'fiscalYear' => $data['FiscalYear'] ?? null,
            'precentOfAnnualBudget' => isset($data['PrecentOfAnnualBudget']) ? (int) $data['PrecentOfAnnualBudget'] : null,
            'startOfFiscalYear' => $data['StartOfFiscalYear'] ?? null,
            'budgetDistributionMethod' => isset($data['BudgetDistributionMethod']) ? (int) $data['BudgetDistributionMethod'] : null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'BudgetKey' => $this->budgetKey,
            'AccountCode' => $this->accountCode,
            'BudgetScenario' => $this->budgetScenario,
            'Description' => $this->description,
            'BudgetAmount' => $this->budgetAmount,
            'BudgetStatus' => $this->budgetStatus,
            'FiscalYear' => $this->fiscalYear,
            'PrecentOfAnnualBudget' => $this->precentOfAnnualBudget,
            'StartOfFiscalYear' => $this->startOfFiscalYear,
            'BudgetDistributionMethod' => $this->budgetDistributionMethod,
        ], fn ($value) => $value !== null);
    }
}

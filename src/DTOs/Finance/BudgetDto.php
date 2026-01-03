<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Finance;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class BudgetDto extends BaseDto
{
    /**
     * @param  array<BudgetLineDto>  $budgetLines
     */
    public function __construct(
        public readonly ?int $numerator = null,
        public readonly ?int $budgetScenario = null,
        public readonly ?string $accountCode = null,
        public readonly ?string $futureAnnualExpenseFlag = null,
        public readonly ?float $futureAnnualExpenseAmount = null,
        public readonly array $budgetLines = [],
    ) {}

    protected static function mapFromArray(array $data): array
    {
        $lines = [];
        if (isset($data['BudgetLines']) && is_array($data['BudgetLines'])) {
            foreach ($data['BudgetLines'] as $line) {
                $lines[] = BudgetLineDto::fromArray($line);
            }
        }

        return [
            'numerator' => isset($data['Numerator']) ? (int) $data['Numerator'] : null,
            'budgetScenario' => isset($data['BudgetScenario']) ? (int) $data['BudgetScenario'] : null,
            'accountCode' => $data['AccountCode'] ?? null,
            'futureAnnualExpenseFlag' => $data['FutureAnnualExpenseFlag'] ?? null,
            'futureAnnualExpenseAmount' => isset($data['FutureAnnualExpenseAmount']) ? (float) $data['FutureAnnualExpenseAmount'] : null,
            'budgetLines' => $lines,
        ];
    }

    public function toArray(): array
    {
        $data = array_filter([
            'Numerator' => $this->numerator,
            'BudgetScenario' => $this->budgetScenario,
            'AccountCode' => $this->accountCode,
            'FutureAnnualExpenseFlag' => $this->futureAnnualExpenseFlag,
            'FutureAnnualExpenseAmount' => $this->futureAnnualExpenseAmount,
        ], fn ($value) => $value !== null);

        if (! empty($this->budgetLines)) {
            $data['BudgetLines'] = array_map(fn (BudgetLineDto $line) => $line->toArray(), $this->budgetLines);
        }

        return $data;
    }
}

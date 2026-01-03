<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Finance;

use SapB1\Toolkit\Builders\BaseBuilder;
use SapB1\Toolkit\DTOs\Finance\BudgetLineDto;

/**
 * @phpstan-consistent-constructor
 */
final class BudgetBuilder extends BaseBuilder
{
    public function budgetScenario(int $scenario): static
    {
        return $this->set('BudgetScenario', $scenario);
    }

    public function accountCode(string $code): static
    {
        return $this->set('AccountCode', $code);
    }

    public function futureAnnualExpenseFlag(string $flag): static
    {
        return $this->set('FutureAnnualExpenseFlag', $flag);
    }

    public function futureAnnualExpenseAmount(float $amount): static
    {
        return $this->set('FutureAnnualExpenseAmount', $amount);
    }

    /**
     * @param  array<BudgetLineDto|array<string, mixed>>  $lines
     */
    public function budgetLines(array $lines): static
    {
        $mapped = array_map(fn ($line) => $line instanceof BudgetLineDto ? $line->toArray() : $line, $lines);

        return $this->set('BudgetLines', $mapped);
    }

    /**
     * @param  BudgetLineDto|array<string, mixed>  $line
     */
    public function addLine(BudgetLineDto|array $line): static
    {
        $lines = $this->get('BudgetLines', []);
        $lines[] = $line instanceof BudgetLineDto ? $line->toArray() : $line;

        return $this->set('BudgetLines', $lines);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

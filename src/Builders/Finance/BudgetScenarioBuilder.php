<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Finance;

use SapB1\Toolkit\Builders\BaseBuilder;

/**
 * @phpstan-consistent-constructor
 */
final class BudgetScenarioBuilder extends BaseBuilder
{
    public function name(string $name): static
    {
        return $this->set('Name', $name);
    }

    public function initialRatioPercentage(string $percentage): static
    {
        return $this->set('InitialRatioPercentage', $percentage);
    }

    public function startofFiscalYear(string $year): static
    {
        return $this->set('StartofFiscalYear', $year);
    }

    public function basicBudget(int $budget): static
    {
        return $this->set('BasicBudget', $budget);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

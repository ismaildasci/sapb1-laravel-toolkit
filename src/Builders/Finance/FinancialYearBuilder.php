<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Finance;

use SapB1\Toolkit\Builders\BaseBuilder;

/**
 * @phpstan-consistent-constructor
 */
final class FinancialYearBuilder extends BaseBuilder
{
    public function code(string $code): static
    {
        return $this->set('Code', $code);
    }

    public function description(string $description): static
    {
        return $this->set('Description', $description);
    }

    public function startDate(string $date): static
    {
        return $this->set('StartDate', $date);
    }

    public function endDate(string $date): static
    {
        return $this->set('EndDate', $date);
    }

    public function assessYear(int $year): static
    {
        return $this->set('AssessYear', $year);
    }

    public function assessYearStart(int $year): static
    {
        return $this->set('AssessYearStart', $year);
    }

    public function assessYearEnd(int $year): static
    {
        return $this->set('AssessYearEnd', $year);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

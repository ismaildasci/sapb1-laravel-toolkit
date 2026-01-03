<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Finance;

use SapB1\Toolkit\Builders\BaseBuilder;

/**
 * @phpstan-consistent-constructor
 */
final class CashDiscountBuilder extends BaseBuilder
{
    public function code(string $code): static
    {
        return $this->set('Code', $code);
    }

    public function name(string $name): static
    {
        return $this->set('Name', $name);
    }

    public function byDate(int $byDate): static
    {
        return $this->set('ByDate', $byDate);
    }

    public function freight(string $freight): static
    {
        return $this->set('Freight', $freight);
    }

    public function tax(string $tax): static
    {
        return $this->set('Tax', $tax);
    }

    public function discountPercent(float $percent): static
    {
        return $this->set('DiscountPercent', $percent);
    }

    public function numOfDays(int $days): static
    {
        return $this->set('NumOfDays', $days);
    }

    public function numOfMonths(int $months): static
    {
        return $this->set('NumOfMonths', $months);
    }

    public function dayOfMonth(int $day): static
    {
        return $this->set('DayOfMonth', $day);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

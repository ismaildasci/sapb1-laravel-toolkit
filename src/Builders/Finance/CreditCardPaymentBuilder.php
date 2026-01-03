<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Finance;

use SapB1\Toolkit\Builders\BaseBuilder;

/**
 * @phpstan-consistent-constructor
 */
final class CreditCardPaymentBuilder extends BaseBuilder
{
    public function dueDateName(string $name): static
    {
        return $this->set('DueDateName', $name);
    }

    public function dueOn(int $dueOn): static
    {
        return $this->set('DueOn', $dueOn);
    }

    public function dueFirstDay(int $day): static
    {
        return $this->set('DueFirstDay', $day);
    }

    public function paymentFirstDayOfMonth(int $day): static
    {
        return $this->set('PaymentFirstDayOfMonth', $day);
    }

    public function numDaysAfterDueDateCode(int $days): static
    {
        return $this->set('NumDaysAfterDueDateCode', $days);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

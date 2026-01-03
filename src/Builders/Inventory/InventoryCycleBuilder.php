<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Inventory;

use SapB1\Toolkit\Builders\BaseBuilder;

/**
 * @phpstan-consistent-constructor
 */
final class InventoryCycleBuilder extends BaseBuilder
{
    public function cycleName(string $name): static
    {
        return $this->set('CycleName', $name);
    }

    public function frequency(int $frequency): static
    {
        return $this->set('Frequency', $frequency);
    }

    public function day(string $day): static
    {
        return $this->set('Day', $day);
    }

    public function hour(string $hour): static
    {
        return $this->set('Hour', $hour);
    }

    public function nextCountingDate(string $date): static
    {
        return $this->set('NextCountingDate', $date);
    }

    public function interval(string $interval): static
    {
        return $this->set('Interval', $interval);
    }

    public function sundayOn(string $value): static
    {
        return $this->set('SundayOn', $value);
    }

    public function mondayOn(string $value): static
    {
        return $this->set('MondayOn', $value);
    }

    public function tuesdayOn(string $value): static
    {
        return $this->set('TuesdayOn', $value);
    }

    public function wednesdayOn(string $value): static
    {
        return $this->set('WednesdayOn', $value);
    }

    public function thursdayOn(string $value): static
    {
        return $this->set('ThursdayOn', $value);
    }

    public function fridayOn(string $value): static
    {
        return $this->set('FridayOn', $value);
    }

    public function saturdayOn(string $value): static
    {
        return $this->set('SaturdayOn', $value);
    }

    public function recurrenceType(string $type): static
    {
        return $this->set('RecurrenceType', $type);
    }

    public function endAfterOccurrences(int $occurrences): static
    {
        return $this->set('EndAfterOccurrences', $occurrences);
    }

    public function endDate(string $date): static
    {
        return $this->set('EndDate', $date);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

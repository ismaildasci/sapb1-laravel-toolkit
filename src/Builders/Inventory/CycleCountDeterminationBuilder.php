<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Inventory;

use SapB1\Toolkit\Builders\BaseBuilder;

/**
 * @phpstan-consistent-constructor
 */
final class CycleCountDeterminationBuilder extends BaseBuilder
{
    public function warehouseCode(string $code): static
    {
        return $this->set('WarehouseCode', $code);
    }

    public function cycleCode(int $code): static
    {
        return $this->set('CycleCode', $code);
    }

    public function alert(string $alert): static
    {
        return $this->set('Alert', $alert);
    }

    public function destinationUser(string $user): static
    {
        return $this->set('DestinationUser', $user);
    }

    public function nextCountingDate(string $date): static
    {
        return $this->set('NextCountingDate', $date);
    }

    public function time(float $time): static
    {
        return $this->set('Time', $time);
    }

    public function excludeItemsWithZeroQuantity(string $value): static
    {
        return $this->set('ExcludeItemsWithZeroQuantity', $value);
    }

    public function changeExistingTimeAndAlert(string $value): static
    {
        return $this->set('ChangeExistingTimeAndAlert', $value);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

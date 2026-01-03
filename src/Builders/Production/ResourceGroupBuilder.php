<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Production;

use SapB1\Toolkit\Builders\BaseBuilder;
use SapB1\Toolkit\Enums\ResourceType;

/**
 * @phpstan-consistent-constructor
 */
final class ResourceGroupBuilder extends BaseBuilder
{
    public function code(int $code): static
    {
        return $this->set('Code', $code);
    }

    public function name(string $name): static
    {
        return $this->set('Name', $name);
    }

    public function type(ResourceType $type): static
    {
        return $this->set('Type', $type->value);
    }

    public function costName1(float $cost): static
    {
        return $this->set('CostName1', $cost);
    }

    public function costName2(float $cost): static
    {
        return $this->set('CostName2', $cost);
    }

    public function costName3(float $cost): static
    {
        return $this->set('CostName3', $cost);
    }

    public function costName4(float $cost): static
    {
        return $this->set('CostName4', $cost);
    }

    public function costName5(float $cost): static
    {
        return $this->set('CostName5', $cost);
    }

    public function costName6(float $cost): static
    {
        return $this->set('CostName6', $cost);
    }

    public function costName7(float $cost): static
    {
        return $this->set('CostName7', $cost);
    }

    public function costName8(float $cost): static
    {
        return $this->set('CostName8', $cost);
    }

    public function costName9(float $cost): static
    {
        return $this->set('CostName9', $cost);
    }

    public function costName10(float $cost): static
    {
        return $this->set('CostName10', $cost);
    }

    public function numOfUnitsText(int $num): static
    {
        return $this->set('NumOfUnitsText', $num);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

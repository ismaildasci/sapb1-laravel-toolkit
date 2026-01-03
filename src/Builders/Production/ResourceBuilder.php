<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Production;

use SapB1\Toolkit\Builders\BaseBuilder;
use SapB1\Toolkit\Enums\BoYesNo;
use SapB1\Toolkit\Enums\ResourceType;

/**
 * @phpstan-consistent-constructor
 */
final class ResourceBuilder extends BaseBuilder
{
    public function code(string $code): static
    {
        return $this->set('Code', $code);
    }

    public function visCode(string $visCode): static
    {
        return $this->set('VisCode', $visCode);
    }

    public function name(string $name): static
    {
        return $this->set('Name', $name);
    }

    public function foreignName(string $name): static
    {
        return $this->set('ForeignName', $name);
    }

    public function type(ResourceType $type): static
    {
        return $this->set('Type', $type->value);
    }

    public function group(int $group): static
    {
        return $this->set('Group', $group);
    }

    public function unitOfMeasure(string $uom): static
    {
        return $this->set('UnitOfMeasure', $uom);
    }

    public function issueMethod(string $method): static
    {
        return $this->set('IssueMethod', $method);
    }

    public function cost1(float $cost): static
    {
        return $this->set('Cost1', $cost);
    }

    public function cost2(float $cost): static
    {
        return $this->set('Cost2', $cost);
    }

    public function cost3(float $cost): static
    {
        return $this->set('Cost3', $cost);
    }

    public function cost4(float $cost): static
    {
        return $this->set('Cost4', $cost);
    }

    public function cost5(float $cost): static
    {
        return $this->set('Cost5', $cost);
    }

    public function cost6(float $cost): static
    {
        return $this->set('Cost6', $cost);
    }

    public function cost7(float $cost): static
    {
        return $this->set('Cost7', $cost);
    }

    public function cost8(float $cost): static
    {
        return $this->set('Cost8', $cost);
    }

    public function cost9(float $cost): static
    {
        return $this->set('Cost9', $cost);
    }

    public function cost10(float $cost): static
    {
        return $this->set('Cost10', $cost);
    }

    public function active(BoYesNo $active): static
    {
        return $this->set('Active', $active->value);
    }

    public function defaultWarehouse(string $warehouse): static
    {
        return $this->set('DefaultWarehouse', $warehouse);
    }

    public function remarks(string $remarks): static
    {
        return $this->set('Remarks', $remarks);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

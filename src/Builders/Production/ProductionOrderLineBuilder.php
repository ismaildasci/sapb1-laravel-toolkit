<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Production;

use SapB1\Toolkit\Builders\BaseBuilder;
use SapB1\Toolkit\Enums\IssueMethod;

/**
 * @phpstan-consistent-constructor
 */
final class ProductionOrderLineBuilder extends BaseBuilder
{
    public function lineNumber(int $lineNumber): static
    {
        return $this->set('LineNumber', $lineNumber);
    }

    public function itemNo(string $itemNo): static
    {
        return $this->set('ItemNo', $itemNo);
    }

    public function baseQuantity(float $quantity): static
    {
        return $this->set('BaseQuantity', $quantity);
    }

    public function plannedQuantity(float $quantity): static
    {
        return $this->set('PlannedQuantity', $quantity);
    }

    public function warehouse(string $warehouse): static
    {
        return $this->set('Warehouse', $warehouse);
    }

    public function issueMethod(IssueMethod $method): static
    {
        return $this->set('IssueMethod', $method->value);
    }

    public function additionalQuantity(float $quantity): static
    {
        return $this->set('AdditionalQuantity', $quantity);
    }

    public function startDate(string $date): static
    {
        return $this->set('StartDate', $date);
    }

    public function endDate(string $date): static
    {
        return $this->set('EndDate', $date);
    }

    public function stagingAreaCode(string $code): static
    {
        return $this->set('StagingAreaCode', $code);
    }

    public function project(int $project): static
    {
        return $this->set('Project', $project);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

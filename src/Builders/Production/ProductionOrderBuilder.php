<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Production;

use SapB1\Toolkit\Builders\BaseBuilder;
use SapB1\Toolkit\Enums\ProductionOrderStatus;
use SapB1\Toolkit\Enums\ProductionOrderType;

/**
 * @phpstan-consistent-constructor
 */
final class ProductionOrderBuilder extends BaseBuilder
{
    public function series(int $series): static
    {
        return $this->set('Series', $series);
    }

    public function itemNo(string $itemNo): static
    {
        return $this->set('ItemNo', $itemNo);
    }

    public function productionOrderStatus(ProductionOrderStatus $status): static
    {
        return $this->set('ProductionOrderStatus', $status->value);
    }

    public function productionOrderType(ProductionOrderType $type): static
    {
        return $this->set('ProductionOrderType', $type->value);
    }

    public function plannedQuantity(float $quantity): static
    {
        return $this->set('PlannedQuantity', $quantity);
    }

    public function postingDate(string $date): static
    {
        return $this->set('PostingDate', $date);
    }

    public function dueDate(string $date): static
    {
        return $this->set('DueDate', $date);
    }

    public function warehouse(string $warehouse): static
    {
        return $this->set('Warehouse', $warehouse);
    }

    public function productionBillOfMaterial(int $bomEntry): static
    {
        return $this->set('ProductionBillOfMaterial', $bomEntry);
    }

    public function project(string $project): static
    {
        return $this->set('Project', $project);
    }

    public function remarks(string $remarks): static
    {
        return $this->set('Remarks', $remarks);
    }

    public function customer(string $customer): static
    {
        return $this->set('Customer', $customer);
    }

    public function priority(int $priority): static
    {
        return $this->set('Priority', $priority);
    }

    public function startDate(string $date): static
    {
        return $this->set('StartDate', $date);
    }

    /**
     * @param  array<ProductionOrderLineBuilder|array<string, mixed>>  $lines
     */
    public function productionOrderLines(array $lines): static
    {
        $builtLines = array_map(
            fn ($line) => $line instanceof ProductionOrderLineBuilder ? $line->build() : $line,
            $lines
        );

        return $this->set('ProductionOrderLines', $builtLines);
    }

    /**
     * @param  ProductionOrderLineBuilder|array<string, mixed>  $line
     */
    public function addLine(ProductionOrderLineBuilder|array $line): static
    {
        $lines = $this->get('ProductionOrderLines', []);
        $lines[] = $line instanceof ProductionOrderLineBuilder ? $line->build() : $line;

        return $this->set('ProductionOrderLines', $lines);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

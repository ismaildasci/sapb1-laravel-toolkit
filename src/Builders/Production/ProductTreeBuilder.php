<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Production;

use SapB1\Toolkit\Builders\BaseBuilder;
use SapB1\Toolkit\Enums\ProductTreeType;

/**
 * @phpstan-consistent-constructor
 */
final class ProductTreeBuilder extends BaseBuilder
{
    public function treeCode(string $code): static
    {
        return $this->set('TreeCode', $code);
    }

    public function treeType(ProductTreeType $type): static
    {
        return $this->set('TreeType', $type->value);
    }

    public function quantity(float $quantity): static
    {
        return $this->set('Quantity', $quantity);
    }

    public function warehouse(string $warehouse): static
    {
        return $this->set('Warehouse', $warehouse);
    }

    public function priceList(int $priceList): static
    {
        return $this->set('PriceList', $priceList);
    }

    public function distributionRule(string $rule): static
    {
        return $this->set('DistributionRule', $rule);
    }

    public function distributionRule2(string $rule): static
    {
        return $this->set('DistributionRule2', $rule);
    }

    public function distributionRule3(string $rule): static
    {
        return $this->set('DistributionRule3', $rule);
    }

    public function distributionRule4(string $rule): static
    {
        return $this->set('DistributionRule4', $rule);
    }

    public function distributionRule5(string $rule): static
    {
        return $this->set('DistributionRule5', $rule);
    }

    public function project(string $project): static
    {
        return $this->set('Project', $project);
    }

    public function remarks(string $remarks): static
    {
        return $this->set('Remarks', $remarks);
    }

    public function hideComponents(string $hide): static
    {
        return $this->set('HideBOMComponentsInPrintout', $hide);
    }

    /**
     * @param  array<ProductTreeLineBuilder|array<string, mixed>>  $lines
     */
    public function productTreeLines(array $lines): static
    {
        $builtLines = array_map(
            fn ($line) => $line instanceof ProductTreeLineBuilder ? $line->build() : $line,
            $lines
        );

        return $this->set('ProductTreeLines', $builtLines);
    }

    /**
     * @param  ProductTreeLineBuilder|array<string, mixed>  $line
     */
    public function addLine(ProductTreeLineBuilder|array $line): static
    {
        $lines = $this->get('ProductTreeLines', []);
        $lines[] = $line instanceof ProductTreeLineBuilder ? $line->build() : $line;

        return $this->set('ProductTreeLines', $lines);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

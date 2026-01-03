<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Inventory;

use SapB1\Toolkit\Builders\BaseBuilder;
use SapB1\Toolkit\DTOs\Inventory\InventoryOpeningBalanceLineDto;

/**
 * @phpstan-consistent-constructor
 */
final class InventoryOpeningBalanceBuilder extends BaseBuilder
{
    public function docDate(string $date): static
    {
        return $this->set('DocDate', $date);
    }

    public function postingDate(string $date): static
    {
        return $this->set('PostingDate', $date);
    }

    public function remarks(string $remarks): static
    {
        return $this->set('Remarks', $remarks);
    }

    public function reference1(string $reference): static
    {
        return $this->set('Reference1', $reference);
    }

    public function reference2(string $reference): static
    {
        return $this->set('Reference2', $reference);
    }

    public function series(int $series): static
    {
        return $this->set('Series', $series);
    }

    public function priceSource(string $source): static
    {
        return $this->set('PriceSource', $source);
    }

    /**
     * @param  array<InventoryOpeningBalanceLineDto|array<string, mixed>>  $lines
     */
    public function inventoryOpeningBalanceLines(array $lines): static
    {
        $mapped = array_map(fn ($line) => $line instanceof InventoryOpeningBalanceLineDto ? $line->toArray() : $line, $lines);

        return $this->set('InventoryOpeningBalanceLines', $mapped);
    }

    /**
     * @param  InventoryOpeningBalanceLineDto|array<string, mixed>  $line
     */
    public function addLine(InventoryOpeningBalanceLineDto|array $line): static
    {
        $lines = $this->get('InventoryOpeningBalanceLines', []);
        $lines[] = $line instanceof InventoryOpeningBalanceLineDto ? $line->toArray() : $line;

        return $this->set('InventoryOpeningBalanceLines', $lines);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

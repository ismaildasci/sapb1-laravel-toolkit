<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Inventory;

use SapB1\Toolkit\Builders\BaseBuilder;
use SapB1\Toolkit\DTOs\Inventory\InventoryPostingLineDto;

/**
 * @phpstan-consistent-constructor
 */
final class InventoryPostingBuilder extends BaseBuilder
{
    public function docDate(string $date): static
    {
        return $this->set('DocDate', $date);
    }

    public function countDate(string $date): static
    {
        return $this->set('CountDate', $date);
    }

    public function countTime(string $time): static
    {
        return $this->set('CountTime', $time);
    }

    public function reference1(string $reference): static
    {
        return $this->set('Reference1', $reference);
    }

    public function reference2(string $reference): static
    {
        return $this->set('Reference2', $reference);
    }

    public function remarks(string $remarks): static
    {
        return $this->set('Remarks', $remarks);
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
     * @param  array<InventoryPostingLineDto|array<string, mixed>>  $lines
     */
    public function inventoryPostingLines(array $lines): static
    {
        $mapped = array_map(fn ($line) => $line instanceof InventoryPostingLineDto ? $line->toArray() : $line, $lines);

        return $this->set('InventoryPostingLines', $mapped);
    }

    /**
     * @param  InventoryPostingLineDto|array<string, mixed>  $line
     */
    public function addLine(InventoryPostingLineDto|array $line): static
    {
        $lines = $this->get('InventoryPostingLines', []);
        $lines[] = $line instanceof InventoryPostingLineDto ? $line->toArray() : $line;

        return $this->set('InventoryPostingLines', $lines);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

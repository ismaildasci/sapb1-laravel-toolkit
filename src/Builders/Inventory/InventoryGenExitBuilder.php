<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Inventory;

use SapB1\Toolkit\Builders\BaseBuilder;
use SapB1\Toolkit\DTOs\Inventory\InventoryGenExitLineDto;

/**
 * Builder for Inventory General Exits (Goods Issue).
 *
 * @phpstan-consistent-constructor
 */
final class InventoryGenExitBuilder extends BaseBuilder
{
    public function docDate(string $date): static
    {
        return $this->set('DocDate', $date);
    }

    public function dueDate(string $date): static
    {
        return $this->set('DueDate', $date);
    }

    public function taxDate(string $date): static
    {
        return $this->set('TaxDate', $date);
    }

    public function reference1(string $reference): static
    {
        return $this->set('Reference1', $reference);
    }

    public function reference2(string $reference): static
    {
        return $this->set('Reference2', $reference);
    }

    public function comments(string $comments): static
    {
        return $this->set('Comments', $comments);
    }

    public function journalMemo(string $memo): static
    {
        return $this->set('JournalMemo', $memo);
    }

    public function docCurrency(string $currency): static
    {
        return $this->set('DocCurrency', $currency);
    }

    public function docRate(float $rate): static
    {
        return $this->set('DocRate', $rate);
    }

    public function series(int $series): static
    {
        return $this->set('Series', $series);
    }

    public function priceList(int $priceList): static
    {
        return $this->set('PriceList', $priceList);
    }

    /**
     * @param  array<InventoryGenExitLineDto|array<string, mixed>>  $lines
     */
    public function documentLines(array $lines): static
    {
        $mapped = array_map(function ($line) {
            if ($line instanceof InventoryGenExitLineDto) {
                return $line->toArray();
            }

            return $line;
        }, $lines);

        return $this->set('DocumentLines', $mapped);
    }

    /**
     * @param  InventoryGenExitLineDto|array<string, mixed>  $line
     */
    public function addLine(InventoryGenExitLineDto|array $line): static
    {
        $lines = $this->get('DocumentLines', []);
        $lines[] = $line instanceof InventoryGenExitLineDto ? $line->toArray() : $line;

        return $this->set('DocumentLines', $lines);
    }

    /**
     * @return array<string, mixed>
     */
    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

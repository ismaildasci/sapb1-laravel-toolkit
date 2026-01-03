<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Inventory;

use SapB1\Toolkit\Builders\BaseBuilder;
use SapB1\Toolkit\DTOs\Inventory\StockTransferLineDto;

/**
 * Builder for Stock Transfers.
 *
 * @phpstan-consistent-constructor
 */
final class StockTransferBuilder extends BaseBuilder
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

    public function cardCode(string $cardCode): static
    {
        return $this->set('CardCode', $cardCode);
    }

    public function fromWarehouse(string $warehouse): static
    {
        return $this->set('FromWarehouse', $warehouse);
    }

    public function toWarehouse(string $warehouse): static
    {
        return $this->set('ToWarehouse', $warehouse);
    }

    public function comments(string $comments): static
    {
        return $this->set('Comments', $comments);
    }

    public function journalMemo(string $memo): static
    {
        return $this->set('JournalMemo', $memo);
    }

    public function salesPersonCode(int $code): static
    {
        return $this->set('SalesPersonCode', $code);
    }

    public function priceList(int $priceList): static
    {
        return $this->set('PriceList', $priceList);
    }

    /**
     * @param  array<StockTransferLineDto|array<string, mixed>>  $lines
     */
    public function stockTransferLines(array $lines): static
    {
        $mappedLines = array_map(
            fn ($line) => $line instanceof StockTransferLineDto ? $line->toArray() : $line,
            $lines
        );

        return $this->set('StockTransferLines', $mappedLines);
    }

    /**
     * @param  StockTransferLineDto|array<string, mixed>  $line
     */
    public function addLine(StockTransferLineDto|array $line): static
    {
        $lines = $this->get('StockTransferLines', []);
        $lines[] = $line instanceof StockTransferLineDto ? $line->toArray() : $line;

        return $this->set('StockTransferLines', $lines);
    }

    /**
     * @return array<string, mixed>
     */
    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

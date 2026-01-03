<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Inventory;

use SapB1\Toolkit\Builders\BaseBuilder;
use SapB1\Toolkit\DTOs\Inventory\InventoryTransferRequestLineDto;

/**
 * @phpstan-consistent-constructor
 */
final class InventoryTransferRequestBuilder extends BaseBuilder
{
    public function docDate(string $date): static
    {
        return $this->set('DocDate', $date);
    }

    public function dueDate(string $date): static
    {
        return $this->set('DueDate', $date);
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

    public function series(int $series): static
    {
        return $this->set('Series', $series);
    }

    /**
     * @param  array<InventoryTransferRequestLineDto|array<string, mixed>>  $lines
     */
    public function stockTransferLines(array $lines): static
    {
        $mapped = array_map(function ($line) {
            return $line instanceof InventoryTransferRequestLineDto ? $line->toArray() : $line;
        }, $lines);

        return $this->set('StockTransferLines', $mapped);
    }

    /**
     * @param  InventoryTransferRequestLineDto|array<string, mixed>  $line
     */
    public function addLine(InventoryTransferRequestLineDto|array $line): static
    {
        $lines = $this->get('StockTransferLines', []);
        $lines[] = $line instanceof InventoryTransferRequestLineDto ? $line->toArray() : $line;

        return $this->set('StockTransferLines', $lines);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

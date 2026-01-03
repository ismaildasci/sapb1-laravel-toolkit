<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Inventory;

use SapB1\Toolkit\Builders\BaseBuilder;
use SapB1\Toolkit\DTOs\Inventory\StockTakingLineDto;

/**
 * @phpstan-consistent-constructor
 */
final class StockTakingBuilder extends BaseBuilder
{
    public function stockTakingDate(string $date): static
    {
        return $this->set('StockTakingDate', $date);
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

    /**
     * @param  array<StockTakingLineDto|array<string, mixed>>  $lines
     */
    public function stockTakingLines(array $lines): static
    {
        $mapped = array_map(fn ($line) => $line instanceof StockTakingLineDto ? $line->toArray() : $line, $lines);

        return $this->set('StockTakingLines', $mapped);
    }

    /**
     * @param  StockTakingLineDto|array<string, mixed>  $line
     */
    public function addLine(StockTakingLineDto|array $line): static
    {
        $lines = $this->get('StockTakingLines', []);
        $lines[] = $line instanceof StockTakingLineDto ? $line->toArray() : $line;

        return $this->set('StockTakingLines', $lines);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

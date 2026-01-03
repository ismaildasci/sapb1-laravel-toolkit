<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Production;

use SapB1\Toolkit\Builders\BaseBuilder;

/**
 * @phpstan-consistent-constructor
 */
final class ResourceCapacityBuilder extends BaseBuilder
{
    public function code(string $code): static
    {
        return $this->set('Code', $code);
    }

    public function warehouse(string $warehouse): static
    {
        return $this->set('Warehouse', $warehouse);
    }

    public function date(string $date): static
    {
        return $this->set('Date', $date);
    }

    public function type(string $type): static
    {
        return $this->set('Type', $type);
    }

    public function capacity(float $capacity): static
    {
        return $this->set('Capacity', $capacity);
    }

    public function sourceType(string $type): static
    {
        return $this->set('SourceType', $type);
    }

    public function sourceEntry(int $entry): static
    {
        return $this->set('SourceEntry', $entry);
    }

    public function sourceLineNum(int $lineNum): static
    {
        return $this->set('SourceLineNum', $lineNum);
    }

    public function action(string $action): static
    {
        return $this->set('Action', $action);
    }

    public function memo(string $memo): static
    {
        return $this->set('Memo', $memo);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

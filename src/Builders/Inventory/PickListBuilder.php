<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Inventory;

use SapB1\Toolkit\Builders\BaseBuilder;
use SapB1\Toolkit\DTOs\Inventory\PickListLineDto;

/**
 * @phpstan-consistent-constructor
 */
final class PickListBuilder extends BaseBuilder
{
    public function name(string $name): static
    {
        return $this->set('Name', $name);
    }

    public function ownerCode(string $code): static
    {
        return $this->set('OwnerCode', $code);
    }

    public function pickDate(string $date): static
    {
        return $this->set('PickDate', $date);
    }

    public function remarks(string $remarks): static
    {
        return $this->set('Remarks', $remarks);
    }

    public function objectType(string $type): static
    {
        return $this->set('ObjectType', $type);
    }

    /**
     * @param  array<PickListLineDto|array<string, mixed>>  $lines
     */
    public function pickListsLines(array $lines): static
    {
        $mapped = array_map(fn ($line) => $line instanceof PickListLineDto ? $line->toArray() : $line, $lines);

        return $this->set('PickListsLines', $mapped);
    }

    /**
     * @param  PickListLineDto|array<string, mixed>  $line
     */
    public function addLine(PickListLineDto|array $line): static
    {
        $lines = $this->get('PickListsLines', []);
        $lines[] = $line instanceof PickListLineDto ? $line->toArray() : $line;

        return $this->set('PickListsLines', $lines);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

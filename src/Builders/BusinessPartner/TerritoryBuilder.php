<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\BusinessPartner;

use SapB1\Toolkit\Builders\BaseBuilder;
use SapB1\Toolkit\Enums\BoYesNo;

/**
 * @phpstan-consistent-constructor
 */
final class TerritoryBuilder extends BaseBuilder
{
    public function territoryID(int $id): static
    {
        return $this->set('TerritoryID', $id);
    }

    public function description(string $description): static
    {
        return $this->set('Description', $description);
    }

    public function locationIndex(int $index): static
    {
        return $this->set('LocationIndex', $index);
    }

    public function inactive(BoYesNo $inactive): static
    {
        return $this->set('Inactive', $inactive->value);
    }

    public function parent(int $parent): static
    {
        return $this->set('Parent', $parent);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

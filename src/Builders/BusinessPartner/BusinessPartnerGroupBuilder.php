<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\BusinessPartner;

use SapB1\Toolkit\Builders\BaseBuilder;
use SapB1\Toolkit\Enums\CardType;

/**
 * @phpstan-consistent-constructor
 */
final class BusinessPartnerGroupBuilder extends BaseBuilder
{
    public function code(int $code): static
    {
        return $this->set('Code', $code);
    }

    public function name(string $name): static
    {
        return $this->set('Name', $name);
    }

    public function type(CardType $type): static
    {
        return $this->set('Type', $type->value);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

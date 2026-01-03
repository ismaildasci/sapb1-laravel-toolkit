<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\BusinessPartner;

use SapB1\Toolkit\Builders\BaseBuilder;

/**
 * @phpstan-consistent-constructor
 */
final class IndustryBuilder extends BaseBuilder
{
    public function industryCode(int $code): static
    {
        return $this->set('IndustryCode', $code);
    }

    public function industryDescription(string $description): static
    {
        return $this->set('IndustryDescription', $description);
    }

    public function industryName(string $name): static
    {
        return $this->set('IndustryName', $name);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

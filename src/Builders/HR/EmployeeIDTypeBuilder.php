<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\HR;

use SapB1\Toolkit\Builders\BaseBuilder;

/**
 * @phpstan-consistent-constructor
 */
final class EmployeeIDTypeBuilder extends BaseBuilder
{
    public function idType(string $idType): static
    {
        return $this->set('IDType', $idType);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

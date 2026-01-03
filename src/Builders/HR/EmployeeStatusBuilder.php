<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\HR;

use SapB1\Toolkit\Builders\BaseBuilder;

/**
 * @phpstan-consistent-constructor
 */
final class EmployeeStatusBuilder extends BaseBuilder
{
    public function name(string $name): static
    {
        return $this->set('Name', $name);
    }

    public function description(string $description): static
    {
        return $this->set('Description', $description);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

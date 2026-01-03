<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Service;

use SapB1\Toolkit\Builders\BaseBuilder;

/**
 * @phpstan-consistent-constructor
 */
final class ServiceCallProblemTypeBuilder extends BaseBuilder
{
    public function problemTypeID(int $id): static
    {
        return $this->set('ProblemTypeID', $id);
    }

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

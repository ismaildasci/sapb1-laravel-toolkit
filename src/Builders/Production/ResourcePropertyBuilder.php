<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Production;

use SapB1\Toolkit\Builders\BaseBuilder;

/**
 * @phpstan-consistent-constructor
 */
final class ResourcePropertyBuilder extends BaseBuilder
{
    public function code(int $code): static
    {
        return $this->set('Code', $code);
    }

    public function name(string $name): static
    {
        return $this->set('Name', $name);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

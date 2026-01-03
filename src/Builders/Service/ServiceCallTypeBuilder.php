<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Service;

use SapB1\Toolkit\Builders\BaseBuilder;

/**
 * @phpstan-consistent-constructor
 */
final class ServiceCallTypeBuilder extends BaseBuilder
{
    public function callTypeID(int $id): static
    {
        return $this->set('CallTypeID', $id);
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

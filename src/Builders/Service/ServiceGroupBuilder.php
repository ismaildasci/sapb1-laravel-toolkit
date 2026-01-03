<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Service;

use SapB1\Toolkit\Builders\BaseBuilder;

/**
 * @phpstan-consistent-constructor
 */
final class ServiceGroupBuilder extends BaseBuilder
{
    public function absEntry(int $entry): static
    {
        return $this->set('AbsEntry', $entry);
    }

    public function serviceGroupCode(string $code): static
    {
        return $this->set('ServiceGroupCode', $code);
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

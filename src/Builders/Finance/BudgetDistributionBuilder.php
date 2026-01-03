<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Finance;

use SapB1\Toolkit\Builders\BaseBuilder;

/**
 * @phpstan-consistent-constructor
 */
final class BudgetDistributionBuilder extends BaseBuilder
{
    public function description(string $description): static
    {
        return $this->set('Description', $description);
    }

    public function january(float $value): static
    {
        return $this->set('January', $value);
    }

    public function february(float $value): static
    {
        return $this->set('February', $value);
    }

    public function march(float $value): static
    {
        return $this->set('March', $value);
    }

    public function april(float $value): static
    {
        return $this->set('April', $value);
    }

    public function may(float $value): static
    {
        return $this->set('May', $value);
    }

    public function june(float $value): static
    {
        return $this->set('June', $value);
    }

    public function july(float $value): static
    {
        return $this->set('July', $value);
    }

    public function august(float $value): static
    {
        return $this->set('August', $value);
    }

    public function september(float $value): static
    {
        return $this->set('September', $value);
    }

    public function october(float $value): static
    {
        return $this->set('October', $value);
    }

    public function november(float $value): static
    {
        return $this->set('November', $value);
    }

    public function december(float $value): static
    {
        return $this->set('December', $value);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

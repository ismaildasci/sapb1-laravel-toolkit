<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\BusinessPartner;

use SapB1\Toolkit\Builders\BaseBuilder;
use SapB1\Toolkit\Enums\BoYesNo;

/**
 * @phpstan-consistent-constructor
 */
final class SalesStageBuilder extends BaseBuilder
{
    public function sequenceNo(int $sequenceNo): static
    {
        return $this->set('SequenceNo', $sequenceNo);
    }

    public function name(string $name): static
    {
        return $this->set('Name', $name);
    }

    public function stageno(int $stageno): static
    {
        return $this->set('Stageno', $stageno);
    }

    public function closingPercentage(float $percentage): static
    {
        return $this->set('ClosingPercentage', $percentage);
    }

    public function cancelled(BoYesNo $cancelled): static
    {
        return $this->set('Cancelled', $cancelled->value);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

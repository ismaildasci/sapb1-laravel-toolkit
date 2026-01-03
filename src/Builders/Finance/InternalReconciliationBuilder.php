<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Finance;

use SapB1\Toolkit\Builders\BaseBuilder;

/**
 * @phpstan-consistent-constructor
 */
final class InternalReconciliationBuilder extends BaseBuilder
{
    public function reconDate(string $date): static
    {
        return $this->set('ReconDate', $date);
    }

    public function cardOrAccount(string $value): static
    {
        return $this->set('CardOrAccount', $value);
    }

    public function reconSum(float $sum): static
    {
        return $this->set('ReconSum', $sum);
    }

    public function reconSumFC(float $sum): static
    {
        return $this->set('ReconSumFC', $sum);
    }

    public function reconSumSC(float $sum): static
    {
        return $this->set('ReconSumSC', $sum);
    }

    public function accountCode(string $code): static
    {
        return $this->set('AccountCode', $code);
    }

    public function cardCode(string $code): static
    {
        return $this->set('CardCode', $code);
    }

    public function cardName(string $name): static
    {
        return $this->set('CardName', $name);
    }

    public function reconciliationType(string $type): static
    {
        return $this->set('ReconciliationType', $type);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

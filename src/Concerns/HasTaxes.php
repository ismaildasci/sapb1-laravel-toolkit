<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Concerns;

trait HasTaxes
{
    protected ?string $taxCode = null;

    protected ?float $taxRate = null;

    protected ?float $taxAmount = null;

    public function withTaxCode(string $taxCode): static
    {
        $this->taxCode = $taxCode;

        return $this;
    }

    public function getTaxCode(): ?string
    {
        return $this->taxCode;
    }

    public function setTaxRate(float $rate): static
    {
        $this->taxRate = $rate;

        return $this;
    }

    public function getTaxRate(): ?float
    {
        return $this->taxRate;
    }

    public function setTaxAmount(float $amount): static
    {
        $this->taxAmount = $amount;

        return $this;
    }

    public function getTaxAmount(): ?float
    {
        return $this->taxAmount;
    }

    public function calculateTax(float $baseAmount): float
    {
        if ($this->taxRate === null) {
            return 0.0;
        }

        return $baseAmount * ($this->taxRate / 100);
    }
}

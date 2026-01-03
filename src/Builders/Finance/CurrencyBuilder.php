<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Finance;

use SapB1\Toolkit\Builders\BaseBuilder;

/**
 * @phpstan-consistent-constructor
 */
final class CurrencyBuilder extends BaseBuilder
{
    public function code(string $code): static
    {
        return $this->set('Code', $code);
    }

    public function name(string $name): static
    {
        return $this->set('Name', $name);
    }

    public function documentsCode(string $code): static
    {
        return $this->set('DocumentsCode', $code);
    }

    public function internationalDescription(string $description): static
    {
        return $this->set('InternationalDescription', $description);
    }

    public function hundredthName(string $name): static
    {
        return $this->set('HundredthName', $name);
    }

    public function englishName(string $name): static
    {
        return $this->set('EnglishName', $name);
    }

    public function englishHundredthName(string $name): static
    {
        return $this->set('EnglishHundredthName', $name);
    }

    public function pluralInternationalDescription(string $description): static
    {
        return $this->set('PluralInternationalDescription', $description);
    }

    public function decimals(string $decimals): static
    {
        return $this->set('Decimals', $decimals);
    }

    public function rounding(string $rounding): static
    {
        return $this->set('Rounding', $rounding);
    }

    public function roundingInPayment(int $rounding): static
    {
        return $this->set('RoundingInPayment', $rounding);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

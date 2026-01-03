<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Sales;

use SapB1\Toolkit\Builders\DocumentBuilder;
use SapB1\Toolkit\Enums\DownPaymentType;

/**
 * Builder for Sales Down Payments.
 *
 * @phpstan-consistent-constructor
 */
final class DownPaymentBuilder extends DocumentBuilder
{
    public function downPaymentType(DownPaymentType $type): static
    {
        return $this->set('DownPaymentType', $type->value);
    }

    public function asRequest(): static
    {
        return $this->downPaymentType(DownPaymentType::Request);
    }

    public function asInvoice(): static
    {
        return $this->downPaymentType(DownPaymentType::Invoice);
    }

    public function downPaymentAmount(float $amount): static
    {
        return $this->set('DownPaymentAmount', $amount);
    }

    public function downPaymentAmountFc(float $amount): static
    {
        return $this->set('DownPaymentAmountFC', $amount);
    }

    public function downPaymentPercentage(float $percentage): static
    {
        return $this->set('DownPaymentPercentage', $percentage);
    }

    public function paymentMethod(string $method): static
    {
        return $this->set('PaymentMethod', $method);
    }

    public function paymentGroupCode(int $code): static
    {
        return $this->set('PaymentGroupCode', $code);
    }
}

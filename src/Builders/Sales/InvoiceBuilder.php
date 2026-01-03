<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Sales;

use SapB1\Toolkit\Builders\DocumentBuilder;

/**
 * Builder for Sales Invoices.
 *
 * @phpstan-consistent-constructor
 */
final class InvoiceBuilder extends DocumentBuilder
{
    public function paymentMethod(string $method): static
    {
        return $this->set('PaymentMethod', $method);
    }

    public function paymentGroupCode(int $code): static
    {
        return $this->set('PaymentGroupCode', $code);
    }

    public function centralBankIndicator(string $indicator): static
    {
        return $this->set('CentralBankIndicator', $indicator);
    }
}

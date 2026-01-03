<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Purchase;

use SapB1\Toolkit\Builders\DocumentBuilder;

/**
 * Builder for Purchase Invoices.
 *
 * @phpstan-consistent-constructor
 */
final class PurchaseInvoiceBuilder extends DocumentBuilder
{
    public function paymentMethod(string $method): static
    {
        return $this->set('PaymentMethod', $method);
    }

    public function paymentGroupCode(int $code): static
    {
        return $this->set('PaymentGroupCode', $code);
    }
}

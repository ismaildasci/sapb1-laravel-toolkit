<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Purchase;

use SapB1\Toolkit\Builders\DocumentBuilder;
use SapB1\Toolkit\Enums\TaxInvoiceType;

/**
 * Builder for Purchase Tax Invoices.
 *
 * @phpstan-consistent-constructor
 */
final class PurchaseTaxInvoiceBuilder extends DocumentBuilder
{
    public function taxInvoiceType(TaxInvoiceType $type): static
    {
        return $this->set('TaxInvoiceType', $type->value);
    }
}

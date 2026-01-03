<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Sales;

use SapB1\Toolkit\Builders\DocumentBuilder;
use SapB1\Toolkit\Enums\TaxInvoiceType;

/**
 * Builder for Sales Tax Invoices.
 *
 * @phpstan-consistent-constructor
 */
final class SalesTaxInvoiceBuilder extends DocumentBuilder
{
    public function taxInvoiceType(TaxInvoiceType $type): static
    {
        return $this->set('TaxInvoiceType', $type->value);
    }

    /**
     * Set as regular tax invoice.
     */
    public function asRegular(): static
    {
        return $this->taxInvoiceType(TaxInvoiceType::Regular);
    }

    /**
     * Set as corrective tax invoice.
     */
    public function asCorrective(): static
    {
        return $this->taxInvoiceType(TaxInvoiceType::Corrective);
    }

    /**
     * Set as simplified tax invoice.
     */
    public function asSimplified(): static
    {
        return $this->taxInvoiceType(TaxInvoiceType::Simplified);
    }
}

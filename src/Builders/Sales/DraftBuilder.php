<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Sales;

use SapB1\Toolkit\Builders\DocumentBuilder;
use SapB1\Toolkit\Enums\DocumentType;

/**
 * Builder for Drafts.
 *
 * @phpstan-consistent-constructor
 */
final class DraftBuilder extends DocumentBuilder
{
    public function docObjectCode(DocumentType $docType): static
    {
        return $this->set('DocObjectCode', $docType->value);
    }

    /**
     * Set as Sales Order draft.
     */
    public function asSalesOrder(): static
    {
        return $this->docObjectCode(DocumentType::SalesOrder);
    }

    /**
     * Set as Sales Invoice draft.
     */
    public function asInvoice(): static
    {
        return $this->docObjectCode(DocumentType::ARInvoice);
    }

    /**
     * Set as Quotation draft.
     */
    public function asQuotation(): static
    {
        return $this->docObjectCode(DocumentType::SalesQuotation);
    }

    /**
     * Set as Delivery Note draft.
     */
    public function asDelivery(): static
    {
        return $this->docObjectCode(DocumentType::DeliveryNote);
    }
}

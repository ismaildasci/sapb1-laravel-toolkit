<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Sales;

use SapB1\Toolkit\Builders\DocumentBuilder;
use SapB1\Toolkit\Enums\CorrectionInvoiceItem;

/**
 * Builder for Correction Invoices.
 *
 * @phpstan-consistent-constructor
 */
final class CorrectionInvoiceBuilder extends DocumentBuilder
{
    public function correctionInvoiceItem(CorrectionInvoiceItem $item): static
    {
        return $this->set('CorrectionInvoiceItem', $item->value);
    }

    public function correctedDocEntry(int $docEntry): static
    {
        return $this->set('CorrectedDocEntry', $docEntry);
    }

    /**
     * Set as "Should Be" correction type.
     */
    public function asShouldBe(): static
    {
        return $this->correctionInvoiceItem(CorrectionInvoiceItem::ShouldBe);
    }

    /**
     * Set as "Was" correction type.
     */
    public function asWas(): static
    {
        return $this->correctionInvoiceItem(CorrectionInvoiceItem::Was);
    }
}

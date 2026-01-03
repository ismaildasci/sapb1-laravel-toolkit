<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Purchase;

use SapB1\Toolkit\Builders\DocumentBuilder;
use SapB1\Toolkit\Enums\CorrectionInvoiceItem;

/**
 * Builder for Correction Purchase Invoice Reversals.
 *
 * @phpstan-consistent-constructor
 */
final class CorrectionPurchaseInvoiceReversalBuilder extends DocumentBuilder
{
    public function correctionInvoiceItem(CorrectionInvoiceItem $item): static
    {
        return $this->set('CorrectionInvoiceItem', $item->value);
    }

    public function originalCorrectionEntry(int $docEntry): static
    {
        return $this->set('OriginalCorrectionEntry', $docEntry);
    }
}

<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Purchase;

use SapB1\Toolkit\Builders\DocumentBuilder;

/**
 * Builder for Purchase Quotations.
 *
 * @phpstan-consistent-constructor
 */
final class PurchaseQuotationBuilder extends DocumentBuilder
{
    public function requiredDate(string $date): static
    {
        return $this->set('RequiredDate', $date);
    }

    public function validUntil(string $date): static
    {
        return $this->set('ValidUntil', $date);
    }
}

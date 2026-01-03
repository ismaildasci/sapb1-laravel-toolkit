<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Sales;

use SapB1\Toolkit\Builders\DocumentBuilder;

/**
 * Builder for Sales Quotations.
 *
 * @phpstan-consistent-constructor
 */
final class QuotationBuilder extends DocumentBuilder
{
    public function validUntil(string $date): static
    {
        return $this->set('ValidUntil', $date);
    }
}

<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Sales;

use SapB1\Toolkit\Builders\DocumentBuilder;

/**
 * Builder for Sales Orders.
 *
 * @phpstan-consistent-constructor
 */
final class OrderBuilder extends DocumentBuilder
{
    public function requiredDate(string $date): static
    {
        return $this->set('RequriedDate', $date);
    }

    public function shipDate(string $date): static
    {
        return $this->set('ShipDate', $date);
    }

    public function pickRemark(string $remark): static
    {
        return $this->set('PickRemark', $remark);
    }
}

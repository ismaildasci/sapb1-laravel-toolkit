<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Sales;

use SapB1\Toolkit\Builders\DocumentBuilder;

/**
 * Builder for Delivery Notes.
 *
 * @phpstan-consistent-constructor
 */
final class DeliveryBuilder extends DocumentBuilder
{
    public function pickRemark(string $remark): static
    {
        return $this->set('PickRemark', $remark);
    }

    public function trackingNumber(string $trackingNumber): static
    {
        return $this->set('TrackingNumber', $trackingNumber);
    }

    public function shipToAddress(string $address): static
    {
        return $this->set('Address', $address);
    }

    public function transportationCode(int $code): static
    {
        return $this->set('TransportationCode', $code);
    }
}

<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Events;

use Illuminate\Foundation\Events\Dispatchable;

class PaymentReceived
{
    use Dispatchable;

    public function __construct(
        public readonly int $paymentDocEntry,
        public readonly string $cardCode,
        public readonly float $amount,
        public readonly string $paymentType = 'incoming',
    ) {}
}

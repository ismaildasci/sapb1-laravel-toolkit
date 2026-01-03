<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Enums;

enum DownPaymentType: string
{
    case Request = 'dpt_Request';
    case Invoice = 'dpt_Invoice';

    public function label(): string
    {
        return match ($this) {
            self::Request => 'Down Payment Request',
            self::Invoice => 'Down Payment Invoice',
        };
    }

    public function isRequest(): bool
    {
        return $this === self::Request;
    }

    public function isInvoice(): bool
    {
        return $this === self::Invoice;
    }
}

<?php

declare(strict_types=1);

use SapB1\Toolkit\Enums\DownPaymentType;

it('has correct values', function () {
    expect(DownPaymentType::Request->value)->toBe('dpt_Request');
    expect(DownPaymentType::Invoice->value)->toBe('dpt_Invoice');
});

it('has correct labels', function () {
    expect(DownPaymentType::Request->label())->toBe('Down Payment Request');
    expect(DownPaymentType::Invoice->label())->toBe('Down Payment Invoice');
});

it('identifies request type', function () {
    expect(DownPaymentType::Request->isRequest())->toBeTrue();
    expect(DownPaymentType::Invoice->isRequest())->toBeFalse();
});

it('identifies invoice type', function () {
    expect(DownPaymentType::Invoice->isInvoice())->toBeTrue();
    expect(DownPaymentType::Request->isInvoice())->toBeFalse();
});

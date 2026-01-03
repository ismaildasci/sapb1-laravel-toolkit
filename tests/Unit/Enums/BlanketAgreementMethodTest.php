<?php

declare(strict_types=1);

use SapB1\Toolkit\Enums\BlanketAgreementMethod;

it('has correct values', function () {
    expect(BlanketAgreementMethod::Monetary->value)->toBe('bamMonetary');
    expect(BlanketAgreementMethod::Quantity->value)->toBe('bamQuantity');
});

it('returns correct labels', function () {
    expect(BlanketAgreementMethod::Monetary->label())->toBe('Monetary');
    expect(BlanketAgreementMethod::Quantity->label())->toBe('Quantity');
});

it('can check if monetary', function () {
    expect(BlanketAgreementMethod::Monetary->isMonetary())->toBeTrue();
    expect(BlanketAgreementMethod::Quantity->isMonetary())->toBeFalse();
});

it('can check if quantity', function () {
    expect(BlanketAgreementMethod::Quantity->isQuantity())->toBeTrue();
    expect(BlanketAgreementMethod::Monetary->isQuantity())->toBeFalse();
});

it('can be created from value', function () {
    expect(BlanketAgreementMethod::from('bamMonetary'))->toBe(BlanketAgreementMethod::Monetary);
    expect(BlanketAgreementMethod::tryFrom('bamInvalid'))->toBeNull();
});

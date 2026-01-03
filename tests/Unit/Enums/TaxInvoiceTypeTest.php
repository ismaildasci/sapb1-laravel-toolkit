<?php

declare(strict_types=1);

use SapB1\Toolkit\Enums\TaxInvoiceType;

it('has correct values', function () {
    expect(TaxInvoiceType::Regular->value)->toBe('titRegular');
    expect(TaxInvoiceType::Corrective->value)->toBe('titCorrective');
    expect(TaxInvoiceType::Simplified->value)->toBe('titSimplified');
});

it('returns correct labels', function () {
    expect(TaxInvoiceType::Regular->label())->toBe('Regular');
    expect(TaxInvoiceType::Corrective->label())->toBe('Corrective');
    expect(TaxInvoiceType::Simplified->label())->toBe('Simplified');
});

it('can check if regular', function () {
    expect(TaxInvoiceType::Regular->isRegular())->toBeTrue();
    expect(TaxInvoiceType::Corrective->isRegular())->toBeFalse();
});

it('can check if corrective', function () {
    expect(TaxInvoiceType::Corrective->isCorrective())->toBeTrue();
    expect(TaxInvoiceType::Regular->isCorrective())->toBeFalse();
});

it('can be created from value', function () {
    expect(TaxInvoiceType::from('titRegular'))->toBe(TaxInvoiceType::Regular);
    expect(TaxInvoiceType::tryFrom('titInvalid'))->toBeNull();
});

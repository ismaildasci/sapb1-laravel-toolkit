<?php

declare(strict_types=1);

use SapB1\Toolkit\Enums\TaxCode;

it('has correct values', function () {
    expect(TaxCode::KDV0->value)->toBe('KDV0');
    expect(TaxCode::KDV8->value)->toBe('KDV8');
    expect(TaxCode::KDV18->value)->toBe('KDV18');
    expect(TaxCode::KDV20->value)->toBe('KDV20');
});

it('has correct labels', function () {
    expect(TaxCode::KDV0->label())->toBe('KDV %0');
    expect(TaxCode::KDV18->label())->toBe('KDV %18');
    expect(TaxCode::EXEMPT->label())->toBe('Exempt');
});

it('returns correct rates', function () {
    expect(TaxCode::KDV0->rate())->toBe(0.0);
    expect(TaxCode::KDV1->rate())->toBe(1.0);
    expect(TaxCode::KDV8->rate())->toBe(8.0);
    expect(TaxCode::KDV10->rate())->toBe(10.0);
    expect(TaxCode::KDV18->rate())->toBe(18.0);
    expect(TaxCode::KDV20->rate())->toBe(20.0);
});

it('identifies exempt tax codes', function () {
    expect(TaxCode::KDV0->isExempt())->toBeTrue();
    expect(TaxCode::EXEMPT->isExempt())->toBeTrue();
    expect(TaxCode::ISTISNA->isExempt())->toBeTrue();
    expect(TaxCode::KDV18->isExempt())->toBeFalse();
});

it('identifies standard tax codes', function () {
    expect(TaxCode::KDV18->isStandard())->toBeTrue();
    expect(TaxCode::KDV20->isStandard())->toBeTrue();
    expect(TaxCode::KDV8->isStandard())->toBeFalse();
});

it('identifies reduced tax codes', function () {
    expect(TaxCode::KDV1->isReduced())->toBeTrue();
    expect(TaxCode::KDV8->isReduced())->toBeTrue();
    expect(TaxCode::KDV10->isReduced())->toBeTrue();
    expect(TaxCode::KDV18->isReduced())->toBeFalse();
});

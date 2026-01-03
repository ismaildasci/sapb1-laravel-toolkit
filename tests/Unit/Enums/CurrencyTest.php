<?php

declare(strict_types=1);

use SapB1\Toolkit\Enums\Currency;

it('has correct values', function () {
    expect(Currency::TRY->value)->toBe('TRY');
    expect(Currency::USD->value)->toBe('USD');
    expect(Currency::EUR->value)->toBe('EUR');
    expect(Currency::GBP->value)->toBe('GBP');
});

it('has correct labels', function () {
    expect(Currency::TRY->label())->toBe('Turkish Lira');
    expect(Currency::USD->label())->toBe('US Dollar');
    expect(Currency::EUR->label())->toBe('Euro');
});

it('has correct symbols', function () {
    expect(Currency::TRY->symbol())->toBe('₺');
    expect(Currency::USD->symbol())->toBe('$');
    expect(Currency::EUR->symbol())->toBe('€');
    expect(Currency::GBP->symbol())->toBe('£');
});

it('returns correct decimal places', function () {
    expect(Currency::TRY->decimalPlaces())->toBe(2);
    expect(Currency::USD->decimalPlaces())->toBe(2);
    expect(Currency::JPY->decimalPlaces())->toBe(0);
});

it('identifies local currency', function () {
    expect(Currency::TRY->isLocal())->toBeTrue();
    expect(Currency::USD->isLocal())->toBeFalse();
});

it('identifies foreign currency', function () {
    expect(Currency::USD->isForeign())->toBeTrue();
    expect(Currency::EUR->isForeign())->toBeTrue();
    expect(Currency::TRY->isForeign())->toBeFalse();
});

it('returns major currencies', function () {
    $major = Currency::majorCurrencies();

    expect($major)->toContain(Currency::TRY);
    expect($major)->toContain(Currency::USD);
    expect($major)->toContain(Currency::EUR);
    expect($major)->toContain(Currency::GBP);
    expect($major)->toHaveCount(4);
});

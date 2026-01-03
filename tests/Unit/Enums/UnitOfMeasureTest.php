<?php

declare(strict_types=1);

use SapB1\Toolkit\Enums\UnitOfMeasure;

it('has correct values', function () {
    expect(UnitOfMeasure::PCS->value)->toBe('PCS');
    expect(UnitOfMeasure::KG->value)->toBe('KG');
    expect(UnitOfMeasure::LT->value)->toBe('LT');
    expect(UnitOfMeasure::M->value)->toBe('M');
});

it('has correct labels', function () {
    expect(UnitOfMeasure::PCS->label())->toBe('Pieces');
    expect(UnitOfMeasure::KG->label())->toBe('Kilogram');
    expect(UnitOfMeasure::LT->label())->toBe('Liter');
});

it('has correct Turkish labels', function () {
    expect(UnitOfMeasure::PCS->labelTr())->toBe('Adet');
    expect(UnitOfMeasure::KG->labelTr())->toBe('Kilogram');
    expect(UnitOfMeasure::LT->labelTr())->toBe('Litre');
    expect(UnitOfMeasure::BOX->labelTr())->toBe('Kutu');
});

it('returns correct category', function () {
    expect(UnitOfMeasure::PCS->category())->toBe('quantity');
    expect(UnitOfMeasure::KG->category())->toBe('weight');
    expect(UnitOfMeasure::LT->category())->toBe('volume');
    expect(UnitOfMeasure::M->category())->toBe('length');
    expect(UnitOfMeasure::M2->category())->toBe('area');
    expect(UnitOfMeasure::HR->category())->toBe('time');
});

it('identifies quantity units', function () {
    expect(UnitOfMeasure::PCS->isQuantity())->toBeTrue();
    expect(UnitOfMeasure::BOX->isQuantity())->toBeTrue();
    expect(UnitOfMeasure::KG->isQuantity())->toBeFalse();
});

it('identifies weight units', function () {
    expect(UnitOfMeasure::KG->isWeight())->toBeTrue();
    expect(UnitOfMeasure::TON->isWeight())->toBeTrue();
    expect(UnitOfMeasure::PCS->isWeight())->toBeFalse();
});

it('identifies volume units', function () {
    expect(UnitOfMeasure::LT->isVolume())->toBeTrue();
    expect(UnitOfMeasure::M3->isVolume())->toBeTrue();
    expect(UnitOfMeasure::KG->isVolume())->toBeFalse();
});

it('identifies length units', function () {
    expect(UnitOfMeasure::M->isLength())->toBeTrue();
    expect(UnitOfMeasure::CM->isLength())->toBeTrue();
    expect(UnitOfMeasure::KG->isLength())->toBeFalse();
});

it('identifies time units', function () {
    expect(UnitOfMeasure::HR->isTime())->toBeTrue();
    expect(UnitOfMeasure::DAY->isTime())->toBeTrue();
    expect(UnitOfMeasure::PCS->isTime())->toBeFalse();
});

it('returns quantity units list', function () {
    $units = UnitOfMeasure::quantityUnits();

    expect($units)->toContain(UnitOfMeasure::PCS);
    expect($units)->toContain(UnitOfMeasure::BOX);
    expect($units)->toHaveCount(6);
});

it('returns weight units list', function () {
    $units = UnitOfMeasure::weightUnits();

    expect($units)->toContain(UnitOfMeasure::KG);
    expect($units)->toContain(UnitOfMeasure::TON);
    expect($units)->toHaveCount(4);
});

it('returns volume units list', function () {
    $units = UnitOfMeasure::volumeUnits();

    expect($units)->toContain(UnitOfMeasure::LT);
    expect($units)->toContain(UnitOfMeasure::M3);
    expect($units)->toHaveCount(4);
});

<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Finance\InternalReconciliationBuilder;

it('creates builder with static method', function () {
    $builder = InternalReconciliationBuilder::create();
    expect($builder)->toBeInstanceOf(InternalReconciliationBuilder::class);
});

it('sets recon date and sum', function () {
    $data = InternalReconciliationBuilder::create()
        ->reconDate('2024-01-15')
        ->reconSum(5000.00)
        ->build();

    expect($data['ReconDate'])->toBe('2024-01-15');
    expect($data['ReconSum'])->toBe(5000.00);
});

it('sets card or account type', function () {
    $data = InternalReconciliationBuilder::create()
        ->cardOrAccount('coaCard')
        ->cardCode('C001')
        ->cardName('ABC Company')
        ->build();

    expect($data['CardOrAccount'])->toBe('coaCard');
    expect($data['CardCode'])->toBe('C001');
    expect($data['CardName'])->toBe('ABC Company');
});

it('sets account code for GL reconciliation', function () {
    $data = InternalReconciliationBuilder::create()
        ->cardOrAccount('coaAccount')
        ->accountCode('1100')
        ->build();

    expect($data['CardOrAccount'])->toBe('coaAccount');
    expect($data['AccountCode'])->toBe('1100');
});

it('sets foreign currency sums', function () {
    $data = InternalReconciliationBuilder::create()
        ->reconSum(5000.00)
        ->reconSumFC(4500.00)
        ->reconSumSC(5500.00)
        ->build();

    expect($data['ReconSum'])->toBe(5000.00);
    expect($data['ReconSumFC'])->toBe(4500.00);
    expect($data['ReconSumSC'])->toBe(5500.00);
});

it('sets reconciliation type', function () {
    $data = InternalReconciliationBuilder::create()
        ->reconciliationType('rtManual')
        ->build();

    expect($data['ReconciliationType'])->toBe('rtManual');
});

it('chains methods fluently', function () {
    $data = InternalReconciliationBuilder::create()
        ->reconDate('2024-01-15')
        ->cardOrAccount('coaCard')
        ->cardCode('C001')
        ->reconSum(5000.00)
        ->build();

    expect($data)->toHaveCount(4);
});

it('excludes null values from build', function () {
    $data = InternalReconciliationBuilder::create()
        ->reconDate('2024-01-15')
        ->build();

    expect($data)->toHaveKey('ReconDate');
    expect($data)->not->toHaveKey('ReconSum');
});

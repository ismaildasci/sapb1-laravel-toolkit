<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Finance\DepositBuilder;

it('creates builder with static method', function () {
    $builder = DepositBuilder::create();
    expect($builder)->toBeInstanceOf(DepositBuilder::class);
});

it('sets deposit type and date', function () {
    $data = DepositBuilder::create()
        ->depositType('dtChecks')
        ->depositDate('2024-01-15')
        ->build();

    expect($data['DepositType'])->toBe('dtChecks');
    expect($data['DepositDate'])->toBe('2024-01-15');
});

it('sets deposit account and currency', function () {
    $data = DepositBuilder::create()
        ->depositAccount('1000')
        ->depositCurrency('TRY')
        ->build();

    expect($data['DepositAccount'])->toBe('1000');
    expect($data['DepositCurrency'])->toBe('TRY');
});

it('chains methods fluently', function () {
    $data = DepositBuilder::create()
        ->depositType('dtChecks')
        ->depositDate('2024-01-15')
        ->depositAccount('1000')
        ->build();

    expect($data)->toHaveCount(3);
});

it('excludes null values from build', function () {
    $data = DepositBuilder::create()
        ->depositType('dtChecks')
        ->build();

    expect($data)->toHaveKey('DepositType');
    expect($data)->not->toHaveKey('DepositDate');
});

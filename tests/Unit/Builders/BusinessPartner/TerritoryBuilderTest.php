<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\BusinessPartner\TerritoryBuilder;
use SapB1\Toolkit\Enums\BoYesNo;

it('creates builder with static method', function () {
    $builder = TerritoryBuilder::create();
    expect($builder)->toBeInstanceOf(TerritoryBuilder::class);
});

it('sets territory ID and description', function () {
    $data = TerritoryBuilder::create()
        ->territoryID(1)
        ->description('North Region')
        ->build();

    expect($data['TerritoryID'])->toBe(1);
    expect($data['Description'])->toBe('North Region');
});

it('sets location index', function () {
    $data = TerritoryBuilder::create()
        ->locationIndex(10)
        ->build();

    expect($data['LocationIndex'])->toBe(10);
});

it('sets inactive status', function () {
    $data = TerritoryBuilder::create()
        ->inactive(BoYesNo::Yes)
        ->build();

    expect($data['Inactive'])->toBe('tYES');
});

it('sets parent territory', function () {
    $data = TerritoryBuilder::create()
        ->parent(1)
        ->build();

    expect($data['Parent'])->toBe(1);
});

it('chains methods fluently', function () {
    $data = TerritoryBuilder::create()
        ->territoryID(1)
        ->description('North Region')
        ->locationIndex(10)
        ->inactive(BoYesNo::No)
        ->build();

    expect($data)->toHaveCount(4);
});

it('excludes null values from build', function () {
    $data = TerritoryBuilder::create()
        ->territoryID(1)
        ->description('North Region')
        ->build();

    expect($data)->toHaveKey('TerritoryID');
    expect($data)->toHaveKey('Description');
    expect($data)->not->toHaveKey('LocationIndex');
    expect($data)->not->toHaveKey('Inactive');
});

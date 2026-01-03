<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Inventory\CycleCountDeterminationBuilder;

it('creates builder with static method', function () {
    $builder = CycleCountDeterminationBuilder::create();

    expect($builder)->toBeInstanceOf(CycleCountDeterminationBuilder::class);
});

it('sets warehouse code', function () {
    $builder = CycleCountDeterminationBuilder::create()
        ->warehouseCode('WH01');

    $data = $builder->build();

    expect($data['WarehouseCode'])->toBe('WH01');
});

it('sets cycle code', function () {
    $builder = CycleCountDeterminationBuilder::create()
        ->cycleCode(1);

    $data = $builder->build();

    expect($data['CycleCode'])->toBe(1);
});

it('sets alert', function () {
    $builder = CycleCountDeterminationBuilder::create()
        ->alert('tYES');

    $data = $builder->build();

    expect($data['Alert'])->toBe('tYES');
});

it('sets destination user', function () {
    $builder = CycleCountDeterminationBuilder::create()
        ->destinationUser('manager');

    $data = $builder->build();

    expect($data['DestinationUser'])->toBe('manager');
});

it('sets next counting date', function () {
    $builder = CycleCountDeterminationBuilder::create()
        ->nextCountingDate('2024-01-22');

    $data = $builder->build();

    expect($data['NextCountingDate'])->toBe('2024-01-22');
});

it('sets time', function () {
    $builder = CycleCountDeterminationBuilder::create()
        ->time(9.5);

    $data = $builder->build();

    expect($data['Time'])->toBe(9.5);
});

it('sets exclude items with zero quantity', function () {
    $builder = CycleCountDeterminationBuilder::create()
        ->excludeItemsWithZeroQuantity('tYES');

    $data = $builder->build();

    expect($data['ExcludeItemsWithZeroQuantity'])->toBe('tYES');
});

it('sets change existing time and alert', function () {
    $builder = CycleCountDeterminationBuilder::create()
        ->changeExistingTimeAndAlert('tNO');

    $data = $builder->build();

    expect($data['ChangeExistingTimeAndAlert'])->toBe('tNO');
});

it('resets builder data', function () {
    $builder = CycleCountDeterminationBuilder::create()
        ->warehouseCode('WH01')
        ->cycleCode(1);

    $builder->reset();
    $data = $builder->build();

    expect($data)->toBeEmpty();
});

it('chains methods fluently', function () {
    $data = CycleCountDeterminationBuilder::create()
        ->warehouseCode('WH01')
        ->cycleCode(1)
        ->alert('tYES')
        ->destinationUser('manager')
        ->nextCountingDate('2024-01-22')
        ->time(9.0)
        ->build();

    expect($data['WarehouseCode'])->toBe('WH01');
    expect($data['CycleCode'])->toBe(1);
    expect($data['Alert'])->toBe('tYES');
    expect($data['DestinationUser'])->toBe('manager');
    expect($data['NextCountingDate'])->toBe('2024-01-22');
    expect($data['Time'])->toBe(9.0);
});

it('excludes null values from build', function () {
    $data = CycleCountDeterminationBuilder::create()
        ->warehouseCode('WH01')
        ->build();

    expect($data)->toHaveKey('WarehouseCode');
    expect($data)->not->toHaveKey('CycleCode');
    expect($data)->not->toHaveKey('Alert');
});

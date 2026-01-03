<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Inventory\PickListBuilder;
use SapB1\Toolkit\DTOs\Inventory\PickListLineDto;

it('creates builder with static method', function () {
    $builder = PickListBuilder::create();

    expect($builder)->toBeInstanceOf(PickListBuilder::class);
});

it('sets name', function () {
    $builder = PickListBuilder::create()
        ->name('Pick List 001');

    $data = $builder->build();

    expect($data['Name'])->toBe('Pick List 001');
});

it('sets owner code', function () {
    $builder = PickListBuilder::create()
        ->ownerCode('USER01');

    $data = $builder->build();

    expect($data['OwnerCode'])->toBe('USER01');
});

it('sets pick date', function () {
    $builder = PickListBuilder::create()
        ->pickDate('2024-01-15');

    $data = $builder->build();

    expect($data['PickDate'])->toBe('2024-01-15');
});

it('sets remarks', function () {
    $builder = PickListBuilder::create()
        ->remarks('Urgent picking required');

    $data = $builder->build();

    expect($data['Remarks'])->toBe('Urgent picking required');
});

it('sets object type', function () {
    $builder = PickListBuilder::create()
        ->objectType('156');

    $data = $builder->build();

    expect($data['ObjectType'])->toBe('156');
});

it('adds lines from array', function () {
    $builder = PickListBuilder::create()
        ->pickListsLines([
            ['ItemCode' => 'ITEM001', 'ReleasedQuantity' => 10],
            ['ItemCode' => 'ITEM002', 'ReleasedQuantity' => 5],
        ]);

    $data = $builder->build();

    expect($data['PickListsLines'])->toHaveCount(2);
    expect($data['PickListsLines'][0]['ItemCode'])->toBe('ITEM001');
});

it('adds lines from DTO', function () {
    $line = new PickListLineDto(
        itemCode: 'ITEM001',
        releasedQuantity: 10.0,
        warehouseCode: 'WH01',
    );

    $builder = PickListBuilder::create()
        ->pickListsLines([$line]);

    $data = $builder->build();

    expect($data['PickListsLines'])->toHaveCount(1);
    expect($data['PickListsLines'][0]['ItemCode'])->toBe('ITEM001');
});

it('adds single line', function () {
    $builder = PickListBuilder::create()
        ->addLine(['ItemCode' => 'ITEM001', 'ReleasedQuantity' => 10])
        ->addLine(['ItemCode' => 'ITEM002', 'ReleasedQuantity' => 5]);

    $data = $builder->build();

    expect($data['PickListsLines'])->toHaveCount(2);
});

it('resets builder data', function () {
    $builder = PickListBuilder::create()
        ->name('Test')
        ->pickDate('2024-01-15');

    $builder->reset();
    $data = $builder->build();

    expect($data)->toBeEmpty();
});

it('chains methods fluently', function () {
    $data = PickListBuilder::create()
        ->name('Pick List 001')
        ->ownerCode('USER01')
        ->pickDate('2024-01-15')
        ->remarks('Fluent test')
        ->addLine(['ItemCode' => 'ITEM001', 'ReleasedQuantity' => 10])
        ->build();

    expect($data['Name'])->toBe('Pick List 001');
    expect($data['OwnerCode'])->toBe('USER01');
    expect($data['PickDate'])->toBe('2024-01-15');
    expect($data['Remarks'])->toBe('Fluent test');
    expect($data['PickListsLines'])->toHaveCount(1);
});

it('excludes null values from build', function () {
    $data = PickListBuilder::create()
        ->name('Test')
        ->build();

    expect($data)->toHaveKey('Name');
    expect($data)->not->toHaveKey('Remarks');
    expect($data)->not->toHaveKey('OwnerCode');
});

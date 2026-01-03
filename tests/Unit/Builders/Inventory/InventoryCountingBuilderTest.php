<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Inventory\InventoryCountingBuilder;
use SapB1\Toolkit\DTOs\Inventory\InventoryCountingLineDto;

it('creates builder with static method', function () {
    $builder = InventoryCountingBuilder::create();

    expect($builder)->toBeInstanceOf(InventoryCountingBuilder::class);
});

it('sets dates', function () {
    $builder = InventoryCountingBuilder::create()
        ->docDate('2024-01-15')
        ->countDate('2024-01-15')
        ->countTime('10:30:00');

    $data = $builder->build();

    expect($data['DocDate'])->toBe('2024-01-15');
    expect($data['CountDate'])->toBe('2024-01-15');
    expect($data['CountTime'])->toBe('10:30:00');
});

it('sets references', function () {
    $builder = InventoryCountingBuilder::create()
        ->reference1('REF001')
        ->reference2('REF002');

    $data = $builder->build();

    expect($data['Reference1'])->toBe('REF001');
    expect($data['Reference2'])->toBe('REF002');
});

it('sets remarks and counting type', function () {
    $builder = InventoryCountingBuilder::create()
        ->remarks('Test remarks')
        ->countingType('ctItemCode');

    $data = $builder->build();

    expect($data['Remarks'])->toBe('Test remarks');
    expect($data['CountingType'])->toBe('ctItemCode');
});

it('sets series', function () {
    $builder = InventoryCountingBuilder::create()
        ->series(10);

    $data = $builder->build();

    expect($data['Series'])->toBe(10);
});

it('adds lines from array', function () {
    $builder = InventoryCountingBuilder::create()
        ->inventoryCountingLines([
            ['ItemCode' => 'ITEM001', 'CountedQuantity' => 10],
            ['ItemCode' => 'ITEM002', 'CountedQuantity' => 5],
        ]);

    $data = $builder->build();

    expect($data['InventoryCountingLines'])->toHaveCount(2);
    expect($data['InventoryCountingLines'][0]['ItemCode'])->toBe('ITEM001');
});

it('adds lines from DTO', function () {
    $line = new InventoryCountingLineDto(
        itemCode: 'ITEM001',
        countedQuantity: 10.0,
        inWarehouseQuantity: 12.0,
        variance: -2.0,
    );

    $builder = InventoryCountingBuilder::create()
        ->inventoryCountingLines([$line]);

    $data = $builder->build();

    expect($data['InventoryCountingLines'])->toHaveCount(1);
    expect($data['InventoryCountingLines'][0]['ItemCode'])->toBe('ITEM001');
    expect($data['InventoryCountingLines'][0]['Variance'])->toBe(-2.0);
});

it('adds single line', function () {
    $builder = InventoryCountingBuilder::create()
        ->addLine(['ItemCode' => 'ITEM001', 'CountedQuantity' => 10])
        ->addLine(['ItemCode' => 'ITEM002', 'CountedQuantity' => 5]);

    $data = $builder->build();

    expect($data['InventoryCountingLines'])->toHaveCount(2);
});

it('resets builder data', function () {
    $builder = InventoryCountingBuilder::create()
        ->docDate('2024-01-15')
        ->remarks('Test');

    $builder->reset();
    $data = $builder->build();

    expect($data)->toBeEmpty();
});

it('chains methods fluently', function () {
    $data = InventoryCountingBuilder::create()
        ->docDate('2024-01-15')
        ->countDate('2024-01-15')
        ->reference1('REF001')
        ->remarks('Fluent test')
        ->countingType('ctItemCode')
        ->addLine(['ItemCode' => 'ITEM001', 'CountedQuantity' => 10])
        ->build();

    expect($data['DocDate'])->toBe('2024-01-15');
    expect($data['CountDate'])->toBe('2024-01-15');
    expect($data['Reference1'])->toBe('REF001');
    expect($data['Remarks'])->toBe('Fluent test');
    expect($data['CountingType'])->toBe('ctItemCode');
    expect($data['InventoryCountingLines'])->toHaveCount(1);
});

it('excludes null values from build', function () {
    $data = InventoryCountingBuilder::create()
        ->docDate('2024-01-15')
        ->build();

    expect($data)->toHaveKey('DocDate');
    expect($data)->not->toHaveKey('Remarks');
    expect($data)->not->toHaveKey('CountingType');
});

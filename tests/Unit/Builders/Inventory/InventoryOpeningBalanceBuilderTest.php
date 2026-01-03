<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Inventory\InventoryOpeningBalanceBuilder;
use SapB1\Toolkit\DTOs\Inventory\InventoryOpeningBalanceLineDto;

it('creates builder with static method', function () {
    $builder = InventoryOpeningBalanceBuilder::create();

    expect($builder)->toBeInstanceOf(InventoryOpeningBalanceBuilder::class);
});

it('sets dates', function () {
    $builder = InventoryOpeningBalanceBuilder::create()
        ->docDate('2024-01-15')
        ->postingDate('2024-01-15');

    $data = $builder->build();

    expect($data['DocDate'])->toBe('2024-01-15');
    expect($data['PostingDate'])->toBe('2024-01-15');
});

it('sets references', function () {
    $builder = InventoryOpeningBalanceBuilder::create()
        ->reference1('REF001')
        ->reference2('REF002');

    $data = $builder->build();

    expect($data['Reference1'])->toBe('REF001');
    expect($data['Reference2'])->toBe('REF002');
});

it('sets remarks and price source', function () {
    $builder = InventoryOpeningBalanceBuilder::create()
        ->remarks('Opening balance for new warehouse')
        ->priceSource('LastEvaluatedPrice');

    $data = $builder->build();

    expect($data['Remarks'])->toBe('Opening balance for new warehouse');
    expect($data['PriceSource'])->toBe('LastEvaluatedPrice');
});

it('sets series', function () {
    $builder = InventoryOpeningBalanceBuilder::create()
        ->series(10);

    $data = $builder->build();

    expect($data['Series'])->toBe(10);
});

it('adds lines from array', function () {
    $builder = InventoryOpeningBalanceBuilder::create()
        ->inventoryOpeningBalanceLines([
            ['ItemCode' => 'ITEM001', 'Quantity' => 100, 'Price' => 50.00],
            ['ItemCode' => 'ITEM002', 'Quantity' => 200, 'Price' => 25.00],
        ]);

    $data = $builder->build();

    expect($data['InventoryOpeningBalanceLines'])->toHaveCount(2);
    expect($data['InventoryOpeningBalanceLines'][0]['ItemCode'])->toBe('ITEM001');
});

it('adds lines from DTO', function () {
    $line = new InventoryOpeningBalanceLineDto(
        itemCode: 'ITEM001',
        quantity: 100.0,
        price: 50.00,
        warehouseCode: 'WH01',
    );

    $builder = InventoryOpeningBalanceBuilder::create()
        ->inventoryOpeningBalanceLines([$line]);

    $data = $builder->build();

    expect($data['InventoryOpeningBalanceLines'])->toHaveCount(1);
    expect($data['InventoryOpeningBalanceLines'][0]['ItemCode'])->toBe('ITEM001');
});

it('adds single line', function () {
    $builder = InventoryOpeningBalanceBuilder::create()
        ->addLine(['ItemCode' => 'ITEM001', 'Quantity' => 100])
        ->addLine(['ItemCode' => 'ITEM002', 'Quantity' => 200]);

    $data = $builder->build();

    expect($data['InventoryOpeningBalanceLines'])->toHaveCount(2);
});

it('resets builder data', function () {
    $builder = InventoryOpeningBalanceBuilder::create()
        ->docDate('2024-01-15')
        ->remarks('Test');

    $builder->reset();
    $data = $builder->build();

    expect($data)->toBeEmpty();
});

it('chains methods fluently', function () {
    $data = InventoryOpeningBalanceBuilder::create()
        ->docDate('2024-01-15')
        ->postingDate('2024-01-15')
        ->reference1('REF001')
        ->remarks('Fluent test')
        ->addLine(['ItemCode' => 'ITEM001', 'Quantity' => 100])
        ->build();

    expect($data['DocDate'])->toBe('2024-01-15');
    expect($data['PostingDate'])->toBe('2024-01-15');
    expect($data['Reference1'])->toBe('REF001');
    expect($data['Remarks'])->toBe('Fluent test');
    expect($data['InventoryOpeningBalanceLines'])->toHaveCount(1);
});

it('excludes null values from build', function () {
    $data = InventoryOpeningBalanceBuilder::create()
        ->docDate('2024-01-15')
        ->build();

    expect($data)->toHaveKey('DocDate');
    expect($data)->not->toHaveKey('Remarks');
    expect($data)->not->toHaveKey('Reference1');
});

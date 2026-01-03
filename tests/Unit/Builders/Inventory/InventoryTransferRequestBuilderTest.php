<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Inventory\InventoryTransferRequestBuilder;
use SapB1\Toolkit\DTOs\Inventory\InventoryTransferRequestLineDto;

it('creates builder with static method', function () {
    $builder = InventoryTransferRequestBuilder::create();

    expect($builder)->toBeInstanceOf(InventoryTransferRequestBuilder::class);
});

it('sets dates', function () {
    $builder = InventoryTransferRequestBuilder::create()
        ->docDate('2024-01-15')
        ->dueDate('2024-01-20');

    $data = $builder->build();

    expect($data['DocDate'])->toBe('2024-01-15');
    expect($data['DueDate'])->toBe('2024-01-20');
});

it('sets warehouses', function () {
    $builder = InventoryTransferRequestBuilder::create()
        ->fromWarehouse('WH01')
        ->toWarehouse('WH02');

    $data = $builder->build();

    expect($data['FromWarehouse'])->toBe('WH01');
    expect($data['ToWarehouse'])->toBe('WH02');
});

it('sets comments', function () {
    $builder = InventoryTransferRequestBuilder::create()
        ->comments('Transfer request for stock replenishment');

    $data = $builder->build();

    expect($data['Comments'])->toBe('Transfer request for stock replenishment');
});

it('sets series', function () {
    $builder = InventoryTransferRequestBuilder::create()
        ->series(10);

    $data = $builder->build();

    expect($data['Series'])->toBe(10);
});

it('adds lines from array', function () {
    $builder = InventoryTransferRequestBuilder::create()
        ->stockTransferLines([
            ['ItemCode' => 'ITEM001', 'Quantity' => 10],
            ['ItemCode' => 'ITEM002', 'Quantity' => 5],
        ]);

    $data = $builder->build();

    expect($data['StockTransferLines'])->toHaveCount(2);
    expect($data['StockTransferLines'][0]['ItemCode'])->toBe('ITEM001');
});

it('adds lines from DTO', function () {
    $line = new InventoryTransferRequestLineDto(
        itemCode: 'ITEM001',
        quantity: 10.0,
        fromWarehouseCode: 'WH01',
        warehouseCode: 'WH02',
    );

    $builder = InventoryTransferRequestBuilder::create()
        ->stockTransferLines([$line]);

    $data = $builder->build();

    expect($data['StockTransferLines'])->toHaveCount(1);
    expect($data['StockTransferLines'][0]['ItemCode'])->toBe('ITEM001');
});

it('adds single line', function () {
    $builder = InventoryTransferRequestBuilder::create()
        ->addLine(['ItemCode' => 'ITEM001', 'Quantity' => 10])
        ->addLine(['ItemCode' => 'ITEM002', 'Quantity' => 5]);

    $data = $builder->build();

    expect($data['StockTransferLines'])->toHaveCount(2);
});

it('resets builder data', function () {
    $builder = InventoryTransferRequestBuilder::create()
        ->docDate('2024-01-15')
        ->fromWarehouse('WH01');

    $builder->reset();
    $data = $builder->build();

    expect($data)->toBeEmpty();
});

it('chains methods fluently', function () {
    $data = InventoryTransferRequestBuilder::create()
        ->docDate('2024-01-15')
        ->dueDate('2024-01-20')
        ->fromWarehouse('WH01')
        ->toWarehouse('WH02')
        ->comments('Fluent test')
        ->addLine(['ItemCode' => 'ITEM001', 'Quantity' => 10])
        ->build();

    expect($data['DocDate'])->toBe('2024-01-15');
    expect($data['DueDate'])->toBe('2024-01-20');
    expect($data['FromWarehouse'])->toBe('WH01');
    expect($data['ToWarehouse'])->toBe('WH02');
    expect($data['Comments'])->toBe('Fluent test');
    expect($data['StockTransferLines'])->toHaveCount(1);
});

it('excludes null values from build', function () {
    $data = InventoryTransferRequestBuilder::create()
        ->docDate('2024-01-15')
        ->build();

    expect($data)->toHaveKey('DocDate');
    expect($data)->not->toHaveKey('Comments');
    expect($data)->not->toHaveKey('FromWarehouse');
});

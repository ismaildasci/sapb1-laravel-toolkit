<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Inventory\InventoryPostingBuilder;
use SapB1\Toolkit\DTOs\Inventory\InventoryPostingLineDto;

it('creates builder with static method', function () {
    $builder = InventoryPostingBuilder::create();

    expect($builder)->toBeInstanceOf(InventoryPostingBuilder::class);
});

it('sets dates', function () {
    $builder = InventoryPostingBuilder::create()
        ->docDate('2024-01-15')
        ->countDate('2024-01-15')
        ->countTime('10:30:00');

    $data = $builder->build();

    expect($data['DocDate'])->toBe('2024-01-15');
    expect($data['CountDate'])->toBe('2024-01-15');
    expect($data['CountTime'])->toBe('10:30:00');
});

it('sets references', function () {
    $builder = InventoryPostingBuilder::create()
        ->reference1('REF001')
        ->reference2('REF002');

    $data = $builder->build();

    expect($data['Reference1'])->toBe('REF001');
    expect($data['Reference2'])->toBe('REF002');
});

it('sets remarks and price source', function () {
    $builder = InventoryPostingBuilder::create()
        ->remarks('Test remarks')
        ->priceSource('LastEvaluatedPrice');

    $data = $builder->build();

    expect($data['Remarks'])->toBe('Test remarks');
    expect($data['PriceSource'])->toBe('LastEvaluatedPrice');
});

it('sets series', function () {
    $builder = InventoryPostingBuilder::create()
        ->series(10);

    $data = $builder->build();

    expect($data['Series'])->toBe(10);
});

it('adds lines from array', function () {
    $builder = InventoryPostingBuilder::create()
        ->inventoryPostingLines([
            ['ItemCode' => 'ITEM001', 'CountedQuantity' => 10],
            ['ItemCode' => 'ITEM002', 'CountedQuantity' => 5],
        ]);

    $data = $builder->build();

    expect($data['InventoryPostingLines'])->toHaveCount(2);
    expect($data['InventoryPostingLines'][0]['ItemCode'])->toBe('ITEM001');
});

it('adds lines from DTO', function () {
    $line = new InventoryPostingLineDto(
        itemCode: 'ITEM001',
        countedQuantity: 10.0,
        warehouseCode: 'WH01',
    );

    $builder = InventoryPostingBuilder::create()
        ->inventoryPostingLines([$line]);

    $data = $builder->build();

    expect($data['InventoryPostingLines'])->toHaveCount(1);
    expect($data['InventoryPostingLines'][0]['ItemCode'])->toBe('ITEM001');
});

it('adds single line', function () {
    $builder = InventoryPostingBuilder::create()
        ->addLine(['ItemCode' => 'ITEM001', 'CountedQuantity' => 10])
        ->addLine(['ItemCode' => 'ITEM002', 'CountedQuantity' => 5]);

    $data = $builder->build();

    expect($data['InventoryPostingLines'])->toHaveCount(2);
});

it('resets builder data', function () {
    $builder = InventoryPostingBuilder::create()
        ->docDate('2024-01-15')
        ->remarks('Test');

    $builder->reset();
    $data = $builder->build();

    expect($data)->toBeEmpty();
});

it('chains methods fluently', function () {
    $data = InventoryPostingBuilder::create()
        ->docDate('2024-01-15')
        ->countDate('2024-01-15')
        ->reference1('REF001')
        ->remarks('Fluent test')
        ->addLine(['ItemCode' => 'ITEM001', 'CountedQuantity' => 10])
        ->build();

    expect($data['DocDate'])->toBe('2024-01-15');
    expect($data['CountDate'])->toBe('2024-01-15');
    expect($data['Reference1'])->toBe('REF001');
    expect($data['Remarks'])->toBe('Fluent test');
    expect($data['InventoryPostingLines'])->toHaveCount(1);
});

it('excludes null values from build', function () {
    $data = InventoryPostingBuilder::create()
        ->docDate('2024-01-15')
        ->build();

    expect($data)->toHaveKey('DocDate');
    expect($data)->not->toHaveKey('Remarks');
    expect($data)->not->toHaveKey('Reference1');
});

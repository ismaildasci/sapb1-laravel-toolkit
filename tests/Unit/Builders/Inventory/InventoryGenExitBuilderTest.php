<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Inventory\InventoryGenExitBuilder;
use SapB1\Toolkit\DTOs\Inventory\InventoryGenExitLineDto;

it('creates builder with static method', function () {
    $builder = InventoryGenExitBuilder::create();

    expect($builder)->toBeInstanceOf(InventoryGenExitBuilder::class);
});

it('sets dates', function () {
    $builder = InventoryGenExitBuilder::create()
        ->docDate('2024-01-15')
        ->dueDate('2024-01-20')
        ->taxDate('2024-01-15');

    $data = $builder->build();

    expect($data['DocDate'])->toBe('2024-01-15');
    expect($data['DueDate'])->toBe('2024-01-20');
    expect($data['TaxDate'])->toBe('2024-01-15');
});

it('sets references', function () {
    $builder = InventoryGenExitBuilder::create()
        ->reference1('REF001')
        ->reference2('REF002');

    $data = $builder->build();

    expect($data['Reference1'])->toBe('REF001');
    expect($data['Reference2'])->toBe('REF002');
});

it('sets comments and memo', function () {
    $builder = InventoryGenExitBuilder::create()
        ->comments('Test comments')
        ->journalMemo('Journal memo');

    $data = $builder->build();

    expect($data['Comments'])->toBe('Test comments');
    expect($data['JournalMemo'])->toBe('Journal memo');
});

it('sets currency and rate', function () {
    $builder = InventoryGenExitBuilder::create()
        ->docCurrency('EUR')
        ->docRate(35.00);

    $data = $builder->build();

    expect($data['DocCurrency'])->toBe('EUR');
    expect($data['DocRate'])->toBe(35.00);
});

it('adds document lines from array', function () {
    $builder = InventoryGenExitBuilder::create()
        ->documentLines([
            ['ItemCode' => 'ITEM001', 'Quantity' => 10],
        ]);

    $data = $builder->build();

    expect($data['DocumentLines'])->toHaveCount(1);
});

it('adds document lines from DTO', function () {
    $line = new InventoryGenExitLineDto(
        itemCode: 'ITEM001',
        quantity: 5.0,
        warehouseCode: 'WH01',
    );

    $builder = InventoryGenExitBuilder::create()
        ->documentLines([$line]);

    $data = $builder->build();

    expect($data['DocumentLines'])->toHaveCount(1);
    expect($data['DocumentLines'][0]['Quantity'])->toBe(5.0);
});

it('adds single line', function () {
    $builder = InventoryGenExitBuilder::create()
        ->addLine(['ItemCode' => 'ITEM001', 'Quantity' => 10])
        ->addLine(['ItemCode' => 'ITEM002', 'Quantity' => 5]);

    $data = $builder->build();

    expect($data['DocumentLines'])->toHaveCount(2);
});

it('resets builder data', function () {
    $builder = InventoryGenExitBuilder::create()
        ->docDate('2024-01-15')
        ->comments('Test');

    $builder->reset();
    $data = $builder->build();

    expect($data)->toBeEmpty();
});

it('chains methods fluently', function () {
    $data = InventoryGenExitBuilder::create()
        ->docDate('2024-01-15')
        ->reference1('EXIT001')
        ->comments('Goods issue')
        ->addLine(['ItemCode' => 'ITEM001', 'Quantity' => 20])
        ->build();

    expect($data['DocDate'])->toBe('2024-01-15');
    expect($data['Reference1'])->toBe('EXIT001');
    expect($data['DocumentLines'])->toHaveCount(1);
});

it('excludes null values from build', function () {
    $data = InventoryGenExitBuilder::create()
        ->docDate('2024-01-15')
        ->build();

    expect($data)->toHaveKey('DocDate');
    expect($data)->not->toHaveKey('Comments');
});

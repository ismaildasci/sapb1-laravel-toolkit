<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Inventory\InventoryGenEntryBuilder;
use SapB1\Toolkit\DTOs\Inventory\InventoryGenEntryLineDto;

it('creates builder with static method', function () {
    $builder = InventoryGenEntryBuilder::create();

    expect($builder)->toBeInstanceOf(InventoryGenEntryBuilder::class);
});

it('sets dates', function () {
    $builder = InventoryGenEntryBuilder::create()
        ->docDate('2024-01-15')
        ->dueDate('2024-01-20')
        ->taxDate('2024-01-15');

    $data = $builder->build();

    expect($data['DocDate'])->toBe('2024-01-15');
    expect($data['DueDate'])->toBe('2024-01-20');
    expect($data['TaxDate'])->toBe('2024-01-15');
});

it('sets references', function () {
    $builder = InventoryGenEntryBuilder::create()
        ->reference1('REF001')
        ->reference2('REF002');

    $data = $builder->build();

    expect($data['Reference1'])->toBe('REF001');
    expect($data['Reference2'])->toBe('REF002');
});

it('sets comments and memo', function () {
    $builder = InventoryGenEntryBuilder::create()
        ->comments('Test comments')
        ->journalMemo('Journal memo');

    $data = $builder->build();

    expect($data['Comments'])->toBe('Test comments');
    expect($data['JournalMemo'])->toBe('Journal memo');
});

it('sets currency and rate', function () {
    $builder = InventoryGenEntryBuilder::create()
        ->docCurrency('USD')
        ->docRate(32.50);

    $data = $builder->build();

    expect($data['DocCurrency'])->toBe('USD');
    expect($data['DocRate'])->toBe(32.50);
});

it('sets series', function () {
    $builder = InventoryGenEntryBuilder::create()
        ->series(10);

    $data = $builder->build();

    expect($data['Series'])->toBe(10);
});

it('adds document lines from array', function () {
    $builder = InventoryGenEntryBuilder::create()
        ->documentLines([
            ['ItemCode' => 'ITEM001', 'Quantity' => 10],
            ['ItemCode' => 'ITEM002', 'Quantity' => 5],
        ]);

    $data = $builder->build();

    expect($data['DocumentLines'])->toHaveCount(2);
    expect($data['DocumentLines'][0]['ItemCode'])->toBe('ITEM001');
});

it('adds document lines from DTO', function () {
    $line = new InventoryGenEntryLineDto(
        itemCode: 'ITEM001',
        quantity: 10.0,
        warehouseCode: 'WH01',
    );

    $builder = InventoryGenEntryBuilder::create()
        ->documentLines([$line]);

    $data = $builder->build();

    expect($data['DocumentLines'])->toHaveCount(1);
    expect($data['DocumentLines'][0]['ItemCode'])->toBe('ITEM001');
});

it('adds single line', function () {
    $builder = InventoryGenEntryBuilder::create()
        ->addLine(['ItemCode' => 'ITEM001', 'Quantity' => 10])
        ->addLine(['ItemCode' => 'ITEM002', 'Quantity' => 5]);

    $data = $builder->build();

    expect($data['DocumentLines'])->toHaveCount(2);
});

it('resets builder data', function () {
    $builder = InventoryGenEntryBuilder::create()
        ->docDate('2024-01-15')
        ->comments('Test');

    $builder->reset();
    $data = $builder->build();

    expect($data)->toBeEmpty();
});

it('chains methods fluently', function () {
    $data = InventoryGenEntryBuilder::create()
        ->docDate('2024-01-15')
        ->reference1('REF001')
        ->comments('Fluent test')
        ->addLine(['ItemCode' => 'ITEM001', 'Quantity' => 10])
        ->build();

    expect($data['DocDate'])->toBe('2024-01-15');
    expect($data['Reference1'])->toBe('REF001');
    expect($data['Comments'])->toBe('Fluent test');
    expect($data['DocumentLines'])->toHaveCount(1);
});

it('excludes null values from build', function () {
    $data = InventoryGenEntryBuilder::create()
        ->docDate('2024-01-15')
        ->build();

    expect($data)->toHaveKey('DocDate');
    expect($data)->not->toHaveKey('Comments');
    expect($data)->not->toHaveKey('Reference1');
});

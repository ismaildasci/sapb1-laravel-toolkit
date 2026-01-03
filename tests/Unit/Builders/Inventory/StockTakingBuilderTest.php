<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Inventory\StockTakingBuilder;
use SapB1\Toolkit\DTOs\Inventory\StockTakingLineDto;

it('creates builder with static method', function () {
    $builder = StockTakingBuilder::create();

    expect($builder)->toBeInstanceOf(StockTakingBuilder::class);
});

it('sets stock taking date', function () {
    $builder = StockTakingBuilder::create()
        ->stockTakingDate('2024-01-15');

    $data = $builder->build();

    expect($data['StockTakingDate'])->toBe('2024-01-15');
});

it('sets references', function () {
    $builder = StockTakingBuilder::create()
        ->reference1('REF001')
        ->reference2('REF002');

    $data = $builder->build();

    expect($data['Reference1'])->toBe('REF001');
    expect($data['Reference2'])->toBe('REF002');
});

it('sets remarks', function () {
    $builder = StockTakingBuilder::create()
        ->remarks('Annual stock taking');

    $data = $builder->build();

    expect($data['Remarks'])->toBe('Annual stock taking');
});

it('sets series', function () {
    $builder = StockTakingBuilder::create()
        ->series(10);

    $data = $builder->build();

    expect($data['Series'])->toBe(10);
});

it('adds lines from array', function () {
    $builder = StockTakingBuilder::create()
        ->stockTakingLines([
            ['ItemCode' => 'ITEM001', 'CountedQuantity' => 100],
            ['ItemCode' => 'ITEM002', 'CountedQuantity' => 50],
        ]);

    $data = $builder->build();

    expect($data['StockTakingLines'])->toHaveCount(2);
    expect($data['StockTakingLines'][0]['ItemCode'])->toBe('ITEM001');
});

it('adds lines from DTO', function () {
    $line = new StockTakingLineDto(
        itemCode: 'ITEM001',
        countedQuantity: 100.0,
        warehouseCode: 'WH01',
    );

    $builder = StockTakingBuilder::create()
        ->stockTakingLines([$line]);

    $data = $builder->build();

    expect($data['StockTakingLines'])->toHaveCount(1);
    expect($data['StockTakingLines'][0]['ItemCode'])->toBe('ITEM001');
});

it('adds single line', function () {
    $builder = StockTakingBuilder::create()
        ->addLine(['ItemCode' => 'ITEM001', 'CountedQuantity' => 100])
        ->addLine(['ItemCode' => 'ITEM002', 'CountedQuantity' => 50]);

    $data = $builder->build();

    expect($data['StockTakingLines'])->toHaveCount(2);
});

it('resets builder data', function () {
    $builder = StockTakingBuilder::create()
        ->stockTakingDate('2024-01-15')
        ->remarks('Test');

    $builder->reset();
    $data = $builder->build();

    expect($data)->toBeEmpty();
});

it('chains methods fluently', function () {
    $data = StockTakingBuilder::create()
        ->stockTakingDate('2024-01-15')
        ->reference1('REF001')
        ->remarks('Fluent test')
        ->series(10)
        ->addLine(['ItemCode' => 'ITEM001', 'CountedQuantity' => 100])
        ->build();

    expect($data['StockTakingDate'])->toBe('2024-01-15');
    expect($data['Reference1'])->toBe('REF001');
    expect($data['Remarks'])->toBe('Fluent test');
    expect($data['Series'])->toBe(10);
    expect($data['StockTakingLines'])->toHaveCount(1);
});

it('excludes null values from build', function () {
    $data = StockTakingBuilder::create()
        ->stockTakingDate('2024-01-15')
        ->build();

    expect($data)->toHaveKey('StockTakingDate');
    expect($data)->not->toHaveKey('Remarks');
    expect($data)->not->toHaveKey('Reference1');
});

<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Inventory\BinLocationBuilder;
use SapB1\Toolkit\Enums\BoYesNo;

it('creates builder with static method', function () {
    $builder = BinLocationBuilder::create();

    expect($builder)->toBeInstanceOf(BinLocationBuilder::class);
});

it('sets bin code', function () {
    $builder = BinLocationBuilder::create()
        ->binCode('WH01-A01-R01-S01');

    $data = $builder->build();

    expect($data['BinCode'])->toBe('WH01-A01-R01-S01');
});

it('sets warehouse code', function () {
    $builder = BinLocationBuilder::create()
        ->warehouseCode('WH01');

    $data = $builder->build();

    expect($data['Warehouse'])->toBe('WH01');
});

it('sets sublevel codes', function () {
    $builder = BinLocationBuilder::create()
        ->sublevelOne(1)
        ->sublevelTwo(2)
        ->sublevelThree(3)
        ->sublevelFour(4);

    $data = $builder->build();

    expect($data['SL1Code'])->toBe(1);
    expect($data['SL2Code'])->toBe(2);
    expect($data['SL3Code'])->toBe(3);
    expect($data['SL4Code'])->toBe(4);
});

it('sets quantity limits', function () {
    $builder = BinLocationBuilder::create()
        ->minimumQty(10.0)
        ->maximumQty(100.0);

    $data = $builder->build();

    expect($data['MinimumQty'])->toBe(10.0);
    expect($data['MaximumQty'])->toBe(100.0);
});

it('sets inactive status', function () {
    $builder = BinLocationBuilder::create()
        ->inactive(BoYesNo::No);

    $data = $builder->build();

    expect($data['Inactive'])->toBe('tNO');
});

it('sets description', function () {
    $builder = BinLocationBuilder::create()
        ->description('Main storage bin');

    $data = $builder->build();

    expect($data['Description'])->toBe('Main storage bin');
});

it('sets bar code', function () {
    $builder = BinLocationBuilder::create()
        ->barCode('1234567890');

    $data = $builder->build();

    expect($data['BarCode'])->toBe('1234567890');
});

it('sets attributes', function () {
    $builder = BinLocationBuilder::create()
        ->attribute1(10)
        ->attribute2(20)
        ->attribute3(30);

    $data = $builder->build();

    expect($data['Attr1Val'])->toBe(10);
    expect($data['Attr2Val'])->toBe(20);
    expect($data['Attr3Val'])->toBe(30);
});

it('sets receiving bin location flag', function () {
    $builder = BinLocationBuilder::create()
        ->receivingBinLocation(BoYesNo::Yes);

    $data = $builder->build();

    expect($data['ReceivingBinLocation'])->toBe('tYES');
});

it('sets exclude auto alloc on issue', function () {
    $builder = BinLocationBuilder::create()
        ->excludeAutoAllocOnIssue(BoYesNo::No);

    $data = $builder->build();

    expect($data['ExcludeAutoAllocOnIssue'])->toBe('tNO');
});

it('sets weight limits', function () {
    $builder = BinLocationBuilder::create()
        ->maximumWeight(500.0)
        ->maximumWeightUnit(1.0);

    $data = $builder->build();

    expect($data['MaximumWeight'])->toBe(500.0);
    expect($data['MaximumWeightUnit'])->toBe(1.0);
});

it('resets builder data', function () {
    $builder = BinLocationBuilder::create()
        ->binCode('WH01-A01')
        ->warehouseCode('WH01');

    $builder->reset();
    $data = $builder->build();

    expect($data)->toBeEmpty();
});

it('chains methods fluently', function () {
    $data = BinLocationBuilder::create()
        ->binCode('WH01-A01-R01-S01')
        ->warehouseCode('WH01')
        ->description('Fluent test')
        ->minimumQty(5.0)
        ->maximumQty(50.0)
        ->inactive(BoYesNo::No)
        ->build();

    expect($data['BinCode'])->toBe('WH01-A01-R01-S01');
    expect($data['Warehouse'])->toBe('WH01');
    expect($data['Description'])->toBe('Fluent test');
    expect($data['MinimumQty'])->toBe(5.0);
    expect($data['MaximumQty'])->toBe(50.0);
    expect($data['Inactive'])->toBe('tNO');
});

it('converts to array via toArray method', function () {
    $builder = BinLocationBuilder::create()
        ->binCode('WH01-A01');

    expect($builder->toArray())->toBe($builder->build());
});

it('excludes null values from build', function () {
    $data = BinLocationBuilder::create()
        ->binCode('WH01-A01')
        ->build();

    expect($data)->toHaveKey('BinCode');
    expect($data)->not->toHaveKey('Warehouse');
    expect($data)->not->toHaveKey('Description');
});

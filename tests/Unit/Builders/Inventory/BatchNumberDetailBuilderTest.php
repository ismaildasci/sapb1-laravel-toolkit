<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Inventory\BatchNumberDetailBuilder;

it('creates builder with static method', function () {
    $builder = BatchNumberDetailBuilder::create();

    expect($builder)->toBeInstanceOf(BatchNumberDetailBuilder::class);
});

it('sets item code', function () {
    $builder = BatchNumberDetailBuilder::create()
        ->itemCode('ITEM001');

    $data = $builder->build();

    expect($data['ItemCode'])->toBe('ITEM001');
});

it('sets batch number', function () {
    $builder = BatchNumberDetailBuilder::create()
        ->batchNumber('BATCH001');

    $data = $builder->build();

    expect($data['Batch'])->toBe('BATCH001');
});

it('sets quantity', function () {
    $builder = BatchNumberDetailBuilder::create()
        ->quantity(100.0);

    $data = $builder->build();

    expect($data['Quantity'])->toBe(100.0);
});

it('sets dates', function () {
    $builder = BatchNumberDetailBuilder::create()
        ->expiryDate('2025-12-31')
        ->manufactureDate('2024-01-01')
        ->admissionDate('2024-01-15');

    $data = $builder->build();

    expect($data['ExpiryDate'])->toBe('2025-12-31');
    expect($data['ManufactureDate'])->toBe('2024-01-01');
    expect($data['AdmissionDate'])->toBe('2024-01-15');
});

it('sets location', function () {
    $builder = BatchNumberDetailBuilder::create()
        ->location('WH01-A1');

    $data = $builder->build();

    expect($data['Location'])->toBe('WH01-A1');
});

it('sets notes', function () {
    $builder = BatchNumberDetailBuilder::create()
        ->notes('Test notes');

    $data = $builder->build();

    expect($data['Notes'])->toBe('Test notes');
});

it('sets serial numbers', function () {
    $builder = BatchNumberDetailBuilder::create()
        ->manufacturerSerialNumber('MSN001')
        ->internalSerialNumber('ISN001');

    $data = $builder->build();

    expect($data['ManufacturerSerialNumber'])->toBe('MSN001');
    expect($data['InternalSerialNumber'])->toBe('ISN001');
});

it('sets attributes', function () {
    $builder = BatchNumberDetailBuilder::create()
        ->attribute1('Attr1')
        ->attribute2('Attr2');

    $data = $builder->build();

    expect($data['Attribute1'])->toBe('Attr1');
    expect($data['Attribute2'])->toBe('Attr2');
});

it('resets builder data', function () {
    $builder = BatchNumberDetailBuilder::create()
        ->itemCode('ITEM001')
        ->batchNumber('BATCH001');

    $builder->reset();
    $data = $builder->build();

    expect($data)->toBeEmpty();
});

it('chains methods fluently', function () {
    $data = BatchNumberDetailBuilder::create()
        ->itemCode('ITEM001')
        ->batchNumber('BATCH001')
        ->quantity(100.0)
        ->expiryDate('2025-12-31')
        ->location('A1')
        ->build();

    expect($data['ItemCode'])->toBe('ITEM001');
    expect($data['Batch'])->toBe('BATCH001');
    expect($data['Quantity'])->toBe(100.0);
    expect($data['ExpiryDate'])->toBe('2025-12-31');
    expect($data['Location'])->toBe('A1');
});

it('excludes null values from build', function () {
    $data = BatchNumberDetailBuilder::create()
        ->itemCode('ITEM001')
        ->build();

    expect($data)->toHaveKey('ItemCode');
    expect($data)->not->toHaveKey('Batch');
    expect($data)->not->toHaveKey('Location');
});

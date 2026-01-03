<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Inventory\SerialNumberDetailBuilder;

it('creates builder with static method', function () {
    $builder = SerialNumberDetailBuilder::create();

    expect($builder)->toBeInstanceOf(SerialNumberDetailBuilder::class);
});

it('sets item code', function () {
    $builder = SerialNumberDetailBuilder::create()
        ->itemCode('ITEM001');

    $data = $builder->build();

    expect($data['ItemCode'])->toBe('ITEM001');
});

it('sets serial number', function () {
    $builder = SerialNumberDetailBuilder::create()
        ->serialNumber('SN001');

    $data = $builder->build();

    expect($data['SerialNumber'])->toBe('SN001');
});

it('sets warehouse code', function () {
    $builder = SerialNumberDetailBuilder::create()
        ->warehouseCode('WH01');

    $data = $builder->build();

    expect($data['WarehouseCode'])->toBe('WH01');
});

it('sets dates', function () {
    $builder = SerialNumberDetailBuilder::create()
        ->expiryDate('2025-12-31')
        ->manufactureDate('2024-01-01')
        ->admissionDate('2024-01-15');

    $data = $builder->build();

    expect($data['ExpiryDate'])->toBe('2025-12-31');
    expect($data['ManufactureDate'])->toBe('2024-01-01');
    expect($data['AdmissionDate'])->toBe('2024-01-15');
});

it('sets location', function () {
    $builder = SerialNumberDetailBuilder::create()
        ->location('A1-R1-S1');

    $data = $builder->build();

    expect($data['Location'])->toBe('A1-R1-S1');
});

it('sets notes', function () {
    $builder = SerialNumberDetailBuilder::create()
        ->notes('Test notes');

    $data = $builder->build();

    expect($data['Notes'])->toBe('Test notes');
});

it('sets serial numbers', function () {
    $builder = SerialNumberDetailBuilder::create()
        ->manufacturerSerialNumber('MSN001')
        ->internalSerialNumber('ISN001');

    $data = $builder->build();

    expect($data['ManufacturerSerialNumber'])->toBe('MSN001');
    expect($data['InternalSerialNumber'])->toBe('ISN001');
});

it('sets attributes', function () {
    $builder = SerialNumberDetailBuilder::create()
        ->attribute1('Attr1')
        ->attribute2('Attr2');

    $data = $builder->build();

    expect($data['Attribute1'])->toBe('Attr1');
    expect($data['Attribute2'])->toBe('Attr2');
});

it('resets builder data', function () {
    $builder = SerialNumberDetailBuilder::create()
        ->itemCode('ITEM001')
        ->serialNumber('SN001');

    $builder->reset();
    $data = $builder->build();

    expect($data)->toBeEmpty();
});

it('chains methods fluently', function () {
    $data = SerialNumberDetailBuilder::create()
        ->itemCode('ITEM001')
        ->serialNumber('SN001')
        ->warehouseCode('WH01')
        ->location('A1-R1-S1')
        ->build();

    expect($data['ItemCode'])->toBe('ITEM001');
    expect($data['SerialNumber'])->toBe('SN001');
    expect($data['WarehouseCode'])->toBe('WH01');
    expect($data['Location'])->toBe('A1-R1-S1');
});

it('excludes null values from build', function () {
    $data = SerialNumberDetailBuilder::create()
        ->itemCode('ITEM001')
        ->build();

    expect($data)->toHaveKey('ItemCode');
    expect($data)->not->toHaveKey('SerialNumber');
    expect($data)->not->toHaveKey('WarehouseCode');
});

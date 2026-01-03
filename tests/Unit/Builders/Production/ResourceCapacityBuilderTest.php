<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Production\ResourceCapacityBuilder;

it('creates builder with static method', function () {
    $builder = ResourceCapacityBuilder::create();
    expect($builder)->toBeInstanceOf(ResourceCapacityBuilder::class);
});

it('sets code', function () {
    $data = ResourceCapacityBuilder::create()
        ->code('RES001')
        ->build();

    expect($data['Code'])->toBe('RES001');
});

it('sets warehouse', function () {
    $data = ResourceCapacityBuilder::create()
        ->warehouse('WH01')
        ->build();

    expect($data['Warehouse'])->toBe('WH01');
});

it('sets date', function () {
    $data = ResourceCapacityBuilder::create()
        ->date('2026-01-15')
        ->build();

    expect($data['Date'])->toBe('2026-01-15');
});

it('sets type', function () {
    $data = ResourceCapacityBuilder::create()
        ->type('rcCapacity')
        ->build();

    expect($data['Type'])->toBe('rcCapacity');
});

it('sets capacity', function () {
    $data = ResourceCapacityBuilder::create()
        ->capacity(8.0)
        ->build();

    expect($data['Capacity'])->toBe(8.0);
});

it('sets source information', function () {
    $data = ResourceCapacityBuilder::create()
        ->sourceType('srcType')
        ->sourceEntry(1)
        ->sourceLineNum(0)
        ->build();

    expect($data['SourceType'])->toBe('srcType');
    expect($data['SourceEntry'])->toBe(1);
    expect($data['SourceLineNum'])->toBe(0);
});

it('sets memo', function () {
    $data = ResourceCapacityBuilder::create()
        ->memo('Daily capacity')
        ->build();

    expect($data['Memo'])->toBe('Daily capacity');
});

it('chains methods fluently', function () {
    $data = ResourceCapacityBuilder::create()
        ->code('RES001')
        ->warehouse('WH01')
        ->date('2026-01-15')
        ->capacity(8.0)
        ->build();

    expect($data)->toHaveCount(4);
});

it('excludes null values from build', function () {
    $data = ResourceCapacityBuilder::create()
        ->code('RES001')
        ->capacity(8.0)
        ->build();

    expect($data)->toHaveKey('Code');
    expect($data)->toHaveKey('Capacity');
    expect($data)->not->toHaveKey('Warehouse');
    expect($data)->not->toHaveKey('Date');
});

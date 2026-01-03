<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Production\ResourceGroupBuilder;
use SapB1\Toolkit\Enums\ResourceType;

it('creates builder with static method', function () {
    $builder = ResourceGroupBuilder::create();
    expect($builder)->toBeInstanceOf(ResourceGroupBuilder::class);
});

it('sets code', function () {
    $data = ResourceGroupBuilder::create()
        ->code(1)
        ->build();

    expect($data['Code'])->toBe(1);
});

it('sets name', function () {
    $data = ResourceGroupBuilder::create()
        ->name('Machines')
        ->build();

    expect($data['Name'])->toBe('Machines');
});

it('sets type', function () {
    $data = ResourceGroupBuilder::create()
        ->type(ResourceType::Machine)
        ->build();

    expect($data['Type'])->toBe('rtMachine');
});

it('sets cost names', function () {
    $data = ResourceGroupBuilder::create()
        ->costName1(50.0)
        ->costName2(25.0)
        ->costName3(10.0)
        ->build();

    expect($data['CostName1'])->toBe(50.0);
    expect($data['CostName2'])->toBe(25.0);
    expect($data['CostName3'])->toBe(10.0);
});

it('sets number of units text', function () {
    $data = ResourceGroupBuilder::create()
        ->numOfUnitsText(10)
        ->build();

    expect($data['NumOfUnitsText'])->toBe(10);
});

it('chains methods fluently', function () {
    $data = ResourceGroupBuilder::create()
        ->code(1)
        ->name('Machines')
        ->type(ResourceType::Machine)
        ->costName1(50.0)
        ->build();

    expect($data)->toHaveCount(4);
});

it('excludes null values from build', function () {
    $data = ResourceGroupBuilder::create()
        ->code(1)
        ->name('Machines')
        ->build();

    expect($data)->toHaveKey('Code');
    expect($data)->toHaveKey('Name');
    expect($data)->not->toHaveKey('Type');
    expect($data)->not->toHaveKey('CostName1');
});

<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Production\ResourceBuilder;
use SapB1\Toolkit\Enums\BoYesNo;
use SapB1\Toolkit\Enums\ResourceType;

it('creates builder with static method', function () {
    $builder = ResourceBuilder::create();
    expect($builder)->toBeInstanceOf(ResourceBuilder::class);
});

it('sets code', function () {
    $data = ResourceBuilder::create()
        ->code('RES001')
        ->build();

    expect($data['Code'])->toBe('RES001');
});

it('sets name', function () {
    $data = ResourceBuilder::create()
        ->name('Machine A')
        ->build();

    expect($data['Name'])->toBe('Machine A');
});

it('sets foreign name', function () {
    $data = ResourceBuilder::create()
        ->foreignName('Makine A')
        ->build();

    expect($data['ForeignName'])->toBe('Makine A');
});

it('sets type', function () {
    $data = ResourceBuilder::create()
        ->type(ResourceType::Machine)
        ->build();

    expect($data['Type'])->toBe('rtMachine');
});

it('sets group', function () {
    $data = ResourceBuilder::create()
        ->group(1)
        ->build();

    expect($data['Group'])->toBe(1);
});

it('sets costs', function () {
    $data = ResourceBuilder::create()
        ->cost1(50.0)
        ->cost2(25.0)
        ->cost3(10.0)
        ->build();

    expect($data['Cost1'])->toBe(50.0);
    expect($data['Cost2'])->toBe(25.0);
    expect($data['Cost3'])->toBe(10.0);
});

it('sets active status', function () {
    $data = ResourceBuilder::create()
        ->active(BoYesNo::Yes)
        ->build();

    expect($data['Active'])->toBe('tYES');
});

it('sets default warehouse', function () {
    $data = ResourceBuilder::create()
        ->defaultWarehouse('WH01')
        ->build();

    expect($data['DefaultWarehouse'])->toBe('WH01');
});

it('chains methods fluently', function () {
    $data = ResourceBuilder::create()
        ->code('RES001')
        ->name('Machine A')
        ->type(ResourceType::Machine)
        ->group(1)
        ->active(BoYesNo::Yes)
        ->build();

    expect($data)->toHaveCount(5);
});

it('excludes null values from build', function () {
    $data = ResourceBuilder::create()
        ->code('RES001')
        ->name('Machine A')
        ->build();

    expect($data)->toHaveKey('Code');
    expect($data)->toHaveKey('Name');
    expect($data)->not->toHaveKey('Type');
    expect($data)->not->toHaveKey('Group');
});

<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Production\ResourcePropertyBuilder;

it('creates builder with static method', function () {
    $builder = ResourcePropertyBuilder::create();
    expect($builder)->toBeInstanceOf(ResourcePropertyBuilder::class);
});

it('sets code', function () {
    $data = ResourcePropertyBuilder::create()
        ->code(1)
        ->build();

    expect($data['Code'])->toBe(1);
});

it('sets name', function () {
    $data = ResourcePropertyBuilder::create()
        ->name('Speed')
        ->build();

    expect($data['Name'])->toBe('Speed');
});

it('chains methods fluently', function () {
    $data = ResourcePropertyBuilder::create()
        ->code(1)
        ->name('Speed')
        ->build();

    expect($data)->toHaveCount(2);
});

it('excludes null values from build', function () {
    $data = ResourcePropertyBuilder::create()
        ->code(1)
        ->build();

    expect($data)->toHaveKey('Code');
    expect($data)->not->toHaveKey('Name');
});

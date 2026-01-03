<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Service\ServiceCallOriginBuilder;

it('creates builder with static method', function () {
    $builder = ServiceCallOriginBuilder::create();
    expect($builder)->toBeInstanceOf(ServiceCallOriginBuilder::class);
});

it('sets origin ID', function () {
    $data = ServiceCallOriginBuilder::create()
        ->originID(1)
        ->build();

    expect($data['OriginID'])->toBe(1);
});

it('sets name', function () {
    $data = ServiceCallOriginBuilder::create()
        ->name('Phone')
        ->build();

    expect($data['Name'])->toBe('Phone');
});

it('sets description', function () {
    $data = ServiceCallOriginBuilder::create()
        ->description('Phone call origin')
        ->build();

    expect($data['Description'])->toBe('Phone call origin');
});

it('chains methods fluently', function () {
    $data = ServiceCallOriginBuilder::create()
        ->originID(1)
        ->name('Phone')
        ->description('Phone call origin')
        ->build();

    expect($data)->toHaveCount(3);
});

it('excludes null values from build', function () {
    $data = ServiceCallOriginBuilder::create()
        ->originID(1)
        ->name('Phone')
        ->build();

    expect($data)->toHaveKey('OriginID');
    expect($data)->toHaveKey('Name');
    expect($data)->not->toHaveKey('Description');
});

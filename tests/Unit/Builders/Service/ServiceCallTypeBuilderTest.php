<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Service\ServiceCallTypeBuilder;

it('creates builder with static method', function () {
    $builder = ServiceCallTypeBuilder::create();
    expect($builder)->toBeInstanceOf(ServiceCallTypeBuilder::class);
});

it('sets call type ID', function () {
    $data = ServiceCallTypeBuilder::create()
        ->callTypeID(1)
        ->build();

    expect($data['CallTypeID'])->toBe(1);
});

it('sets name', function () {
    $data = ServiceCallTypeBuilder::create()
        ->name('Installation')
        ->build();

    expect($data['Name'])->toBe('Installation');
});

it('sets description', function () {
    $data = ServiceCallTypeBuilder::create()
        ->description('Product installation service')
        ->build();

    expect($data['Description'])->toBe('Product installation service');
});

it('chains methods fluently', function () {
    $data = ServiceCallTypeBuilder::create()
        ->callTypeID(1)
        ->name('Installation')
        ->description('Product installation service')
        ->build();

    expect($data)->toHaveCount(3);
});

it('excludes null values from build', function () {
    $data = ServiceCallTypeBuilder::create()
        ->callTypeID(1)
        ->name('Installation')
        ->build();

    expect($data)->toHaveKey('CallTypeID');
    expect($data)->toHaveKey('Name');
    expect($data)->not->toHaveKey('Description');
});

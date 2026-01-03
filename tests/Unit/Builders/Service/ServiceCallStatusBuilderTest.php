<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Service\ServiceCallStatusBuilder;

it('creates builder with static method', function () {
    $builder = ServiceCallStatusBuilder::create();
    expect($builder)->toBeInstanceOf(ServiceCallStatusBuilder::class);
});

it('sets status ID', function () {
    $data = ServiceCallStatusBuilder::create()
        ->statusId(1)
        ->build();

    expect($data['StatusId'])->toBe(1);
});

it('sets name', function () {
    $data = ServiceCallStatusBuilder::create()
        ->name('Open')
        ->build();

    expect($data['Name'])->toBe('Open');
});

it('sets description', function () {
    $data = ServiceCallStatusBuilder::create()
        ->description('Open service call')
        ->build();

    expect($data['Description'])->toBe('Open service call');
});

it('chains methods fluently', function () {
    $data = ServiceCallStatusBuilder::create()
        ->statusId(1)
        ->name('Open')
        ->description('Open service call')
        ->build();

    expect($data)->toHaveCount(3);
});

it('excludes null values from build', function () {
    $data = ServiceCallStatusBuilder::create()
        ->statusId(1)
        ->name('Open')
        ->build();

    expect($data)->toHaveKey('StatusId');
    expect($data)->toHaveKey('Name');
    expect($data)->not->toHaveKey('Description');
});

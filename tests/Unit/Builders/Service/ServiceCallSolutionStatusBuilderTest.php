<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Service\ServiceCallSolutionStatusBuilder;

it('creates builder with static method', function () {
    $builder = ServiceCallSolutionStatusBuilder::create();
    expect($builder)->toBeInstanceOf(ServiceCallSolutionStatusBuilder::class);
});

it('sets status ID', function () {
    $data = ServiceCallSolutionStatusBuilder::create()
        ->statusId(1)
        ->build();

    expect($data['StatusId'])->toBe(1);
});

it('sets name', function () {
    $data = ServiceCallSolutionStatusBuilder::create()
        ->name('Pending')
        ->build();

    expect($data['Name'])->toBe('Pending');
});

it('sets description', function () {
    $data = ServiceCallSolutionStatusBuilder::create()
        ->description('Solution pending')
        ->build();

    expect($data['Description'])->toBe('Solution pending');
});

it('chains methods fluently', function () {
    $data = ServiceCallSolutionStatusBuilder::create()
        ->statusId(1)
        ->name('Pending')
        ->description('Solution pending')
        ->build();

    expect($data)->toHaveCount(3);
});

it('excludes null values from build', function () {
    $data = ServiceCallSolutionStatusBuilder::create()
        ->statusId(1)
        ->name('Pending')
        ->build();

    expect($data)->toHaveKey('StatusId');
    expect($data)->toHaveKey('Name');
    expect($data)->not->toHaveKey('Description');
});

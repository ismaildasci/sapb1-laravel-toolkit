<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Service\ServiceCallProblemSubTypeBuilder;

it('creates builder with static method', function () {
    $builder = ServiceCallProblemSubTypeBuilder::create();
    expect($builder)->toBeInstanceOf(ServiceCallProblemSubTypeBuilder::class);
});

it('sets problem sub type ID', function () {
    $data = ServiceCallProblemSubTypeBuilder::create()
        ->problemSubTypeID(1)
        ->build();

    expect($data['ProblemSubTypeID'])->toBe(1);
});

it('sets name', function () {
    $data = ServiceCallProblemSubTypeBuilder::create()
        ->name('Keyboard')
        ->build();

    expect($data['Name'])->toBe('Keyboard');
});

it('sets description', function () {
    $data = ServiceCallProblemSubTypeBuilder::create()
        ->description('Keyboard related issue')
        ->build();

    expect($data['Description'])->toBe('Keyboard related issue');
});

it('chains methods fluently', function () {
    $data = ServiceCallProblemSubTypeBuilder::create()
        ->problemSubTypeID(1)
        ->name('Keyboard')
        ->description('Keyboard related issue')
        ->build();

    expect($data)->toHaveCount(3);
});

it('excludes null values from build', function () {
    $data = ServiceCallProblemSubTypeBuilder::create()
        ->problemSubTypeID(1)
        ->name('Keyboard')
        ->build();

    expect($data)->toHaveKey('ProblemSubTypeID');
    expect($data)->toHaveKey('Name');
    expect($data)->not->toHaveKey('Description');
});

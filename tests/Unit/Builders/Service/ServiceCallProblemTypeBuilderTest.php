<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Service\ServiceCallProblemTypeBuilder;

it('creates builder with static method', function () {
    $builder = ServiceCallProblemTypeBuilder::create();
    expect($builder)->toBeInstanceOf(ServiceCallProblemTypeBuilder::class);
});

it('sets problem type ID', function () {
    $data = ServiceCallProblemTypeBuilder::create()
        ->problemTypeID(1)
        ->build();

    expect($data['ProblemTypeID'])->toBe(1);
});

it('sets name', function () {
    $data = ServiceCallProblemTypeBuilder::create()
        ->name('Hardware')
        ->build();

    expect($data['Name'])->toBe('Hardware');
});

it('sets description', function () {
    $data = ServiceCallProblemTypeBuilder::create()
        ->description('Hardware related issue')
        ->build();

    expect($data['Description'])->toBe('Hardware related issue');
});

it('chains methods fluently', function () {
    $data = ServiceCallProblemTypeBuilder::create()
        ->problemTypeID(1)
        ->name('Hardware')
        ->description('Hardware related issue')
        ->build();

    expect($data)->toHaveCount(3);
});

it('excludes null values from build', function () {
    $data = ServiceCallProblemTypeBuilder::create()
        ->problemTypeID(1)
        ->name('Hardware')
        ->build();

    expect($data)->toHaveKey('ProblemTypeID');
    expect($data)->toHaveKey('Name');
    expect($data)->not->toHaveKey('Description');
});

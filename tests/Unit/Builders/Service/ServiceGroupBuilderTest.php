<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Service\ServiceGroupBuilder;

it('creates builder with static method', function () {
    $builder = ServiceGroupBuilder::create();
    expect($builder)->toBeInstanceOf(ServiceGroupBuilder::class);
});

it('sets abs entry', function () {
    $data = ServiceGroupBuilder::create()
        ->absEntry(1)
        ->build();

    expect($data['AbsEntry'])->toBe(1);
});

it('sets service group code', function () {
    $data = ServiceGroupBuilder::create()
        ->serviceGroupCode('SG001')
        ->build();

    expect($data['ServiceGroupCode'])->toBe('SG001');
});

it('sets description', function () {
    $data = ServiceGroupBuilder::create()
        ->description('Premium Support')
        ->build();

    expect($data['Description'])->toBe('Premium Support');
});

it('chains methods fluently', function () {
    $data = ServiceGroupBuilder::create()
        ->absEntry(1)
        ->serviceGroupCode('SG001')
        ->description('Premium Support')
        ->build();

    expect($data)->toHaveCount(3);
});

it('excludes null values from build', function () {
    $data = ServiceGroupBuilder::create()
        ->absEntry(1)
        ->serviceGroupCode('SG001')
        ->build();

    expect($data)->toHaveKey('AbsEntry');
    expect($data)->toHaveKey('ServiceGroupCode');
    expect($data)->not->toHaveKey('Description');
});

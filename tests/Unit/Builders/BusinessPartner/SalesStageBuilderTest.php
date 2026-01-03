<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\BusinessPartner\SalesStageBuilder;
use SapB1\Toolkit\Enums\BoYesNo;

it('creates builder with static method', function () {
    $builder = SalesStageBuilder::create();
    expect($builder)->toBeInstanceOf(SalesStageBuilder::class);
});

it('sets sequence number and name', function () {
    $data = SalesStageBuilder::create()
        ->sequenceNo(1)
        ->name('Initial Contact')
        ->build();

    expect($data['SequenceNo'])->toBe(1);
    expect($data['Name'])->toBe('Initial Contact');
});

it('sets stage number', function () {
    $data = SalesStageBuilder::create()
        ->stageno(1)
        ->build();

    expect($data['Stageno'])->toBe(1);
});

it('sets closing percentage', function () {
    $data = SalesStageBuilder::create()
        ->closingPercentage(25.5)
        ->build();

    expect($data['ClosingPercentage'])->toBe(25.5);
});

it('sets cancelled status', function () {
    $data = SalesStageBuilder::create()
        ->cancelled(BoYesNo::Yes)
        ->build();

    expect($data['Cancelled'])->toBe('tYES');
});

it('chains methods fluently', function () {
    $data = SalesStageBuilder::create()
        ->sequenceNo(1)
        ->name('Initial Contact')
        ->stageno(1)
        ->closingPercentage(10.0)
        ->build();

    expect($data)->toHaveCount(4);
});

it('excludes null values from build', function () {
    $data = SalesStageBuilder::create()
        ->sequenceNo(1)
        ->name('Initial Contact')
        ->build();

    expect($data)->toHaveKey('SequenceNo');
    expect($data)->toHaveKey('Name');
    expect($data)->not->toHaveKey('Stageno');
    expect($data)->not->toHaveKey('ClosingPercentage');
});

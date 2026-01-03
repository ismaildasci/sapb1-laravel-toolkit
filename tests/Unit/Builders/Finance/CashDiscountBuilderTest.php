<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Finance\CashDiscountBuilder;

it('creates builder with static method', function () {
    $builder = CashDiscountBuilder::create();
    expect($builder)->toBeInstanceOf(CashDiscountBuilder::class);
});

it('sets code and name', function () {
    $data = CashDiscountBuilder::create()
        ->code('CD01')
        ->name('Early Payment 2%')
        ->build();

    expect($data['Code'])->toBe('CD01');
    expect($data['Name'])->toBe('Early Payment 2%');
});

it('sets discount percent and by date', function () {
    $data = CashDiscountBuilder::create()
        ->discountPercent(2.5)
        ->byDate(1)
        ->build();

    expect($data['DiscountPercent'])->toBe(2.5);
    expect($data['ByDate'])->toBe(1);
});

it('sets number of days', function () {
    $data = CashDiscountBuilder::create()
        ->numOfDays(10)
        ->numOfMonths(1)
        ->dayOfMonth(15)
        ->build();

    expect($data['NumOfDays'])->toBe(10);
    expect($data['NumOfMonths'])->toBe(1);
    expect($data['DayOfMonth'])->toBe(15);
});

it('chains methods fluently', function () {
    $data = CashDiscountBuilder::create()
        ->code('CD01')
        ->name('Test')
        ->discountPercent(2.0)
        ->build();

    expect($data)->toHaveCount(3);
});

it('excludes null values from build', function () {
    $data = CashDiscountBuilder::create()
        ->code('CD01')
        ->build();

    expect($data)->toHaveKey('Code');
    expect($data)->not->toHaveKey('DiscountPercent');
});

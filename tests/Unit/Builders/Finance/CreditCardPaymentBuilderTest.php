<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Finance\CreditCardPaymentBuilder;

it('creates builder with static method', function () {
    $builder = CreditCardPaymentBuilder::create();
    expect($builder)->toBeInstanceOf(CreditCardPaymentBuilder::class);
});

it('sets due date name', function () {
    $data = CreditCardPaymentBuilder::create()
        ->dueDateName('Due in 30 days')
        ->build();

    expect($data['DueDateName'])->toBe('Due in 30 days');
});

it('sets due on and due first day', function () {
    $data = CreditCardPaymentBuilder::create()
        ->dueOn(15)
        ->dueFirstDay(1)
        ->build();

    expect($data['DueOn'])->toBe(15);
    expect($data['DueFirstDay'])->toBe(1);
});

it('sets payment first day of month', function () {
    $data = CreditCardPaymentBuilder::create()
        ->paymentFirstDayOfMonth(1)
        ->numDaysAfterDueDateCode(30)
        ->build();

    expect($data['PaymentFirstDayOfMonth'])->toBe(1);
    expect($data['NumDaysAfterDueDateCode'])->toBe(30);
});

it('chains methods fluently', function () {
    $data = CreditCardPaymentBuilder::create()
        ->dueDateName('Test')
        ->dueOn(15)
        ->dueFirstDay(1)
        ->build();

    expect($data)->toHaveCount(3);
});

it('excludes null values from build', function () {
    $data = CreditCardPaymentBuilder::create()
        ->dueDateName('Test')
        ->build();

    expect($data)->toHaveKey('DueDateName');
    expect($data)->not->toHaveKey('DueOn');
});

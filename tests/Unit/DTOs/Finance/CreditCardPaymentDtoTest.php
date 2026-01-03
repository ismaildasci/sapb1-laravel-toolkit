<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Finance\CreditCardPaymentDto;

it('creates from array', function () {
    $data = [
        'DueDateCode' => 1,
        'DueDateName' => 'Due in 30 days',
        'DueOn' => 15,
        'DueFirstDay' => 1,
    ];

    $dto = CreditCardPaymentDto::fromArray($data);

    expect($dto->dueDateCode)->toBe(1);
    expect($dto->dueDateName)->toBe('Due in 30 days');
    expect($dto->dueOn)->toBe(15);
    expect($dto->dueFirstDay)->toBe(1);
});

it('creates from response', function () {
    $response = [
        'DueDateCode' => 2,
        'DueDateName' => 'Due in 60 days',
        'PaymentFirstDayOfMonth' => 1,
        'NumDaysAfterDueDateCode' => 30,
    ];

    $dto = CreditCardPaymentDto::fromResponse($response);

    expect($dto->dueDateCode)->toBe(2);
    expect($dto->dueDateName)->toBe('Due in 60 days');
    expect($dto->paymentFirstDayOfMonth)->toBe(1);
    expect($dto->numDaysAfterDueDateCode)->toBe(30);
});

it('converts to array', function () {
    $dto = new CreditCardPaymentDto(
        dueDateCode: 1,
        dueDateName: 'Test Payment',
        dueOn: 15,
    );

    $array = $dto->toArray();

    expect($array['DueDateCode'])->toBe(1);
    expect($array['DueDateName'])->toBe('Test Payment');
    expect($array['DueOn'])->toBe(15);
});

it('excludes null values in toArray', function () {
    $dto = new CreditCardPaymentDto(
        dueDateCode: 1,
        dueDateName: 'Test',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('DueDateCode');
    expect($array)->toHaveKey('DueDateName');
    expect($array)->not->toHaveKey('DueOn');
    expect($array)->not->toHaveKey('DueFirstDay');
});

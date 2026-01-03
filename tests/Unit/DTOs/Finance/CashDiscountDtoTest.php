<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Finance\CashDiscountDto;

it('creates from array', function () {
    $data = [
        'Code' => 'CD01',
        'Name' => 'Early Payment 2%',
        'ByDate' => 1,
        'Freight' => 'tNO',
        'Tax' => 'tNO',
    ];

    $dto = CashDiscountDto::fromArray($data);

    expect($dto->code)->toBe('CD01');
    expect($dto->name)->toBe('Early Payment 2%');
    expect($dto->byDate)->toBe(1);
    expect($dto->freight)->toBe('tNO');
    expect($dto->tax)->toBe('tNO');
});

it('creates from response', function () {
    $response = [
        'Code' => 'CD02',
        'Name' => 'Net Payment',
        'DiscountPercent' => 2.5,
        'NumOfDays' => 10,
    ];

    $dto = CashDiscountDto::fromResponse($response);

    expect($dto->code)->toBe('CD02');
    expect($dto->name)->toBe('Net Payment');
    expect($dto->discountPercent)->toBe(2.5);
    expect($dto->numOfDays)->toBe(10);
});

it('converts to array', function () {
    $dto = new CashDiscountDto(
        code: 'CD01',
        name: 'Test Discount',
        discountPercent: 2.5,
    );

    $array = $dto->toArray();

    expect($array['Code'])->toBe('CD01');
    expect($array['Name'])->toBe('Test Discount');
    expect($array['DiscountPercent'])->toBe(2.5);
});

it('excludes null values in toArray', function () {
    $dto = new CashDiscountDto(
        code: 'CD01',
        name: 'Test',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('Code');
    expect($array)->toHaveKey('Name');
    expect($array)->not->toHaveKey('DiscountPercent');
    expect($array)->not->toHaveKey('ByDate');
});

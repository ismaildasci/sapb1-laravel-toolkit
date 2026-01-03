<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Finance\BudgetDistributionDto;

it('creates from array', function () {
    $data = [
        'DivisionCode' => 1,
        'Description' => 'Monthly Distribution',
        'January' => 8.33,
        'February' => 8.33,
        'March' => 8.34,
    ];

    $dto = BudgetDistributionDto::fromArray($data);

    expect($dto->divisionCode)->toBe(1);
    expect($dto->description)->toBe('Monthly Distribution');
    expect($dto->january)->toBe(8.33);
    expect($dto->february)->toBe(8.33);
    expect($dto->march)->toBe(8.34);
});

it('creates from response', function () {
    $response = [
        'DivisionCode' => 2,
        'Description' => 'Quarterly Distribution',
        'April' => 25.0,
        'May' => 0.0,
        'June' => 0.0,
    ];

    $dto = BudgetDistributionDto::fromResponse($response);

    expect($dto->divisionCode)->toBe(2);
    expect($dto->description)->toBe('Quarterly Distribution');
    expect($dto->april)->toBe(25.0);
    expect($dto->may)->toBe(0.0);
});

it('converts to array', function () {
    $dto = new BudgetDistributionDto(
        divisionCode: 1,
        description: 'Test Distribution',
        january: 100.0,
    );

    $array = $dto->toArray();

    expect($array['DivisionCode'])->toBe(1);
    expect($array['Description'])->toBe('Test Distribution');
    expect($array['January'])->toBe(100.0);
});

it('excludes null values in toArray', function () {
    $dto = new BudgetDistributionDto(
        divisionCode: 1,
        description: 'Test',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('DivisionCode');
    expect($array)->toHaveKey('Description');
    expect($array)->not->toHaveKey('January');
    expect($array)->not->toHaveKey('February');
});

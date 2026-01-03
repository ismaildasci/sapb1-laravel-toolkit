<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Finance\BudgetDto;
use SapB1\Toolkit\DTOs\Finance\BudgetLineDto;

it('creates from array', function () {
    $data = [
        'Numerator' => 1,
        'BudgetScenario' => 1,
        'AccountCode' => '4000',
        'FutureAnnualExpenseFlag' => 'tNO',
    ];

    $dto = BudgetDto::fromArray($data);

    expect($dto->numerator)->toBe(1);
    expect($dto->budgetScenario)->toBe(1);
    expect($dto->accountCode)->toBe('4000');
    expect($dto->futureAnnualExpenseFlag)->toBe('tNO');
});

it('creates from response', function () {
    $response = [
        'Numerator' => 2,
        'BudgetScenario' => 1,
        'FutureAnnualExpenseAmount' => 120000.00,
    ];

    $dto = BudgetDto::fromResponse($response);

    expect($dto->numerator)->toBe(2);
    expect($dto->budgetScenario)->toBe(1);
    expect($dto->futureAnnualExpenseAmount)->toBe(120000.00);
});

it('converts to array', function () {
    $dto = new BudgetDto(
        numerator: 1,
        budgetScenario: 1,
        accountCode: '4000',
    );

    $array = $dto->toArray();

    expect($array['Numerator'])->toBe(1);
    expect($array['BudgetScenario'])->toBe(1);
    expect($array['AccountCode'])->toBe('4000');
});

it('handles budget lines', function () {
    $data = [
        'Numerator' => 1,
        'BudgetLines' => [
            ['RowNumber' => 0, 'BudgetAmount' => 10000.00],
            ['RowNumber' => 1, 'BudgetAmount' => 12000.00],
        ],
    ];

    $dto = BudgetDto::fromArray($data);

    expect($dto->budgetLines)->toHaveCount(2);
    expect($dto->budgetLines[0])->toBeInstanceOf(BudgetLineDto::class);
});

it('excludes null values in toArray', function () {
    $dto = new BudgetDto(
        numerator: 1,
        budgetScenario: 1,
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('Numerator');
    expect($array)->toHaveKey('BudgetScenario');
    expect($array)->not->toHaveKey('AccountCode');
    expect($array)->not->toHaveKey('FutureAnnualExpenseAmount');
});

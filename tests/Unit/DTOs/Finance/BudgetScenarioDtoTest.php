<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Finance\BudgetScenarioDto;

it('creates from array', function () {
    $data = [
        'Numerator' => 1,
        'Name' => 'Main Budget',
        'InitialRatioPercentage' => '100.0',
        'StartofFiscalYear' => '2024-01-01',
    ];

    $dto = BudgetScenarioDto::fromArray($data);

    expect($dto->numerator)->toBe(1);
    expect($dto->name)->toBe('Main Budget');
    expect($dto->initialRatioPercentage)->toBe('100.0');
    expect($dto->startofFiscalYear)->toBe('2024-01-01');
});

it('creates from response', function () {
    $response = [
        'Numerator' => 2,
        'Name' => 'Alternative Budget',
        'BasicBudget' => 1,
    ];

    $dto = BudgetScenarioDto::fromResponse($response);

    expect($dto->numerator)->toBe(2);
    expect($dto->name)->toBe('Alternative Budget');
    expect($dto->basicBudget)->toBe(1);
});

it('converts to array', function () {
    $dto = new BudgetScenarioDto(
        numerator: 1,
        name: 'Test Budget',
        initialRatioPercentage: '100.0',
    );

    $array = $dto->toArray();

    expect($array['Numerator'])->toBe(1);
    expect($array['Name'])->toBe('Test Budget');
    expect($array['InitialRatioPercentage'])->toBe('100.0');
});

it('excludes null values in toArray', function () {
    $dto = new BudgetScenarioDto(
        numerator: 1,
        name: 'Test',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('Numerator');
    expect($array)->toHaveKey('Name');
    expect($array)->not->toHaveKey('InitialRatioPercentage');
    expect($array)->not->toHaveKey('BasicBudget');
});

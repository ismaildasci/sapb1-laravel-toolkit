<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Finance\BudgetScenarioBuilder;

it('creates builder with static method', function () {
    $builder = BudgetScenarioBuilder::create();
    expect($builder)->toBeInstanceOf(BudgetScenarioBuilder::class);
});

it('sets name and initial ratio', function () {
    $data = BudgetScenarioBuilder::create()
        ->name('Main Budget')
        ->initialRatioPercentage('100.0')
        ->build();

    expect($data['Name'])->toBe('Main Budget');
    expect($data['InitialRatioPercentage'])->toBe('100.0');
});

it('sets start of fiscal year', function () {
    $data = BudgetScenarioBuilder::create()
        ->startofFiscalYear('2024-01-01')
        ->basicBudget(1)
        ->build();

    expect($data['StartofFiscalYear'])->toBe('2024-01-01');
    expect($data['BasicBudget'])->toBe(1);
});

it('chains methods fluently', function () {
    $data = BudgetScenarioBuilder::create()
        ->name('Test Budget')
        ->initialRatioPercentage('100.0')
        ->startofFiscalYear('2024-01-01')
        ->build();

    expect($data)->toHaveCount(3);
});

it('excludes null values from build', function () {
    $data = BudgetScenarioBuilder::create()
        ->name('Test')
        ->build();

    expect($data)->toHaveKey('Name');
    expect($data)->not->toHaveKey('InitialRatioPercentage');
});

<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Finance\BudgetBuilder;

it('creates builder with static method', function () {
    $builder = BudgetBuilder::create();
    expect($builder)->toBeInstanceOf(BudgetBuilder::class);
});

it('sets budget scenario and account code', function () {
    $data = BudgetBuilder::create()
        ->budgetScenario(1)
        ->accountCode('4000')
        ->build();

    expect($data['BudgetScenario'])->toBe(1);
    expect($data['AccountCode'])->toBe('4000');
});

it('sets future expense settings', function () {
    $data = BudgetBuilder::create()
        ->futureAnnualExpenseFlag('tNO')
        ->futureAnnualExpenseAmount(120000.00)
        ->build();

    expect($data['FutureAnnualExpenseFlag'])->toBe('tNO');
    expect($data['FutureAnnualExpenseAmount'])->toBe(120000.00);
});

it('adds budget lines from array', function () {
    $data = BudgetBuilder::create()
        ->budgetLines([
            ['RowNumber' => 0, 'BudgetAmount' => 10000.00],
            ['RowNumber' => 1, 'BudgetAmount' => 12000.00],
        ])
        ->build();

    expect($data['BudgetLines'])->toHaveCount(2);
});

it('adds single line', function () {
    $data = BudgetBuilder::create()
        ->addLine(['RowNumber' => 0, 'BudgetAmount' => 10000.00])
        ->addLine(['RowNumber' => 1, 'BudgetAmount' => 12000.00])
        ->build();

    expect($data['BudgetLines'])->toHaveCount(2);
});

it('chains methods fluently', function () {
    $data = BudgetBuilder::create()
        ->budgetScenario(1)
        ->accountCode('4000')
        ->addLine(['BudgetAmount' => 10000.00])
        ->build();

    expect($data)->toHaveCount(3);
});

it('excludes null values from build', function () {
    $data = BudgetBuilder::create()
        ->budgetScenario(1)
        ->build();

    expect($data)->toHaveKey('BudgetScenario');
    expect($data)->not->toHaveKey('AccountCode');
});

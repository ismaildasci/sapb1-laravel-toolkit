<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Finance\BudgetDistributionBuilder;

it('creates builder with static method', function () {
    $builder = BudgetDistributionBuilder::create();
    expect($builder)->toBeInstanceOf(BudgetDistributionBuilder::class);
});

it('sets description', function () {
    $data = BudgetDistributionBuilder::create()
        ->description('Monthly Distribution')
        ->build();

    expect($data['Description'])->toBe('Monthly Distribution');
});

it('sets monthly percentages', function () {
    $data = BudgetDistributionBuilder::create()
        ->january(8.33)
        ->february(8.33)
        ->march(8.34)
        ->build();

    expect($data['January'])->toBe(8.33);
    expect($data['February'])->toBe(8.33);
    expect($data['March'])->toBe(8.34);
});

it('sets all months', function () {
    $data = BudgetDistributionBuilder::create()
        ->april(8.33)
        ->may(8.33)
        ->june(8.34)
        ->july(8.33)
        ->august(8.33)
        ->september(8.34)
        ->october(8.33)
        ->november(8.33)
        ->december(8.34)
        ->build();

    expect($data)->toHaveCount(9);
});

it('chains methods fluently', function () {
    $data = BudgetDistributionBuilder::create()
        ->description('Test')
        ->january(100.0)
        ->february(0.0)
        ->build();

    expect($data)->toHaveCount(3);
});

it('excludes null values from build', function () {
    $data = BudgetDistributionBuilder::create()
        ->description('Test')
        ->build();

    expect($data)->toHaveKey('Description');
    expect($data)->not->toHaveKey('January');
});

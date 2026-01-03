<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Finance\FinancialYearBuilder;

it('creates builder with static method', function () {
    $builder = FinancialYearBuilder::create();
    expect($builder)->toBeInstanceOf(FinancialYearBuilder::class);
});

it('sets code and description', function () {
    $data = FinancialYearBuilder::create()
        ->code('2024')
        ->description('Fiscal Year 2024')
        ->build();

    expect($data['Code'])->toBe('2024');
    expect($data['Description'])->toBe('Fiscal Year 2024');
});

it('sets start and end dates', function () {
    $data = FinancialYearBuilder::create()
        ->startDate('2024-01-01')
        ->endDate('2024-12-31')
        ->build();

    expect($data['StartDate'])->toBe('2024-01-01');
    expect($data['EndDate'])->toBe('2024-12-31');
});

it('sets assess year', function () {
    $data = FinancialYearBuilder::create()
        ->assessYear(2024)
        ->assessYearStart(2024)
        ->assessYearEnd(2024)
        ->build();

    expect($data['AssessYear'])->toBe(2024);
    expect($data['AssessYearStart'])->toBe(2024);
    expect($data['AssessYearEnd'])->toBe(2024);
});

it('chains methods fluently', function () {
    $data = FinancialYearBuilder::create()
        ->code('2024')
        ->description('Test')
        ->startDate('2024-01-01')
        ->build();

    expect($data)->toHaveCount(3);
});

it('excludes null values from build', function () {
    $data = FinancialYearBuilder::create()
        ->code('2024')
        ->build();

    expect($data)->toHaveKey('Code');
    expect($data)->not->toHaveKey('Description');
});

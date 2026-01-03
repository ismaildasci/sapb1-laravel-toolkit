<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\BusinessPartner\SalesPersonBuilder;
use SapB1\Toolkit\Enums\BoYesNo;

it('creates builder with static method', function () {
    $builder = SalesPersonBuilder::create();
    expect($builder)->toBeInstanceOf(SalesPersonBuilder::class);
});

it('sets sales employee code and name', function () {
    $data = SalesPersonBuilder::create()
        ->salesEmployeeCode(1)
        ->salesEmployeeName('John Smith')
        ->build();

    expect($data['SalesEmployeeCode'])->toBe(1);
    expect($data['SalesEmployeeName'])->toBe('John Smith');
});

it('sets commission fields', function () {
    $data = SalesPersonBuilder::create()
        ->commissionForSalesEmployee(5.5)
        ->commissionGroup(1)
        ->build();

    expect($data['CommissionForSalesEmployee'])->toBe(5.5);
    expect($data['CommissionGroup'])->toBe(1);
});

it('sets locked status', function () {
    $data = SalesPersonBuilder::create()
        ->locked(BoYesNo::Yes)
        ->build();

    expect($data['Locked'])->toBe('tYES');
});

it('sets active status', function () {
    $data = SalesPersonBuilder::create()
        ->active(BoYesNo::Yes)
        ->build();

    expect($data['Active'])->toBe('tYES');
});

it('sets employee ID', function () {
    $data = SalesPersonBuilder::create()
        ->employeeID(100)
        ->build();

    expect($data['EmployeeID'])->toBe(100);
});

it('chains methods fluently', function () {
    $data = SalesPersonBuilder::create()
        ->salesEmployeeCode(1)
        ->salesEmployeeName('John Smith')
        ->commissionForSalesEmployee(5.5)
        ->active(BoYesNo::Yes)
        ->build();

    expect($data)->toHaveCount(4);
});

it('excludes null values from build', function () {
    $data = SalesPersonBuilder::create()
        ->salesEmployeeCode(1)
        ->salesEmployeeName('John Smith')
        ->build();

    expect($data)->toHaveKey('SalesEmployeeCode');
    expect($data)->toHaveKey('SalesEmployeeName');
    expect($data)->not->toHaveKey('CommissionForSalesEmployee');
    expect($data)->not->toHaveKey('Active');
});

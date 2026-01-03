<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\BusinessPartner\SalesPersonDto;
use SapB1\Toolkit\Enums\BoYesNo;

it('creates from array', function () {
    $data = [
        'SalesEmployeeCode' => 1,
        'SalesEmployeeName' => 'John Smith',
        'CommissionForSalesEmployee' => 5.5,
        'CommissionGroup' => 1,
        'Locked' => 'tNO',
        'EmployeeID' => 100,
        'Active' => 'tYES',
    ];

    $dto = SalesPersonDto::fromArray($data);

    expect($dto->salesEmployeeCode)->toBe(1);
    expect($dto->salesEmployeeName)->toBe('John Smith');
    expect($dto->commissionForSalesEmployee)->toBe(5.5);
    expect($dto->commissionGroup)->toBe(1);
    expect($dto->locked)->toBe(BoYesNo::No);
    expect($dto->employeeID)->toBe(100);
    expect($dto->active)->toBe(BoYesNo::Yes);
});

it('creates from response', function () {
    $response = [
        'SalesEmployeeCode' => 2,
        'SalesEmployeeName' => 'Jane Doe',
        'CommissionForSalesEmployee' => 7.5,
        'Active' => 'tYES',
    ];

    $dto = SalesPersonDto::fromResponse($response);

    expect($dto->salesEmployeeCode)->toBe(2);
    expect($dto->salesEmployeeName)->toBe('Jane Doe');
    expect($dto->commissionForSalesEmployee)->toBe(7.5);
    expect($dto->active)->toBe(BoYesNo::Yes);
});

it('converts to array', function () {
    $dto = new SalesPersonDto(
        salesEmployeeCode: 1,
        salesEmployeeName: 'John Smith',
        commissionForSalesEmployee: 5.5,
        active: BoYesNo::Yes,
    );

    $array = $dto->toArray();

    expect($array['SalesEmployeeCode'])->toBe(1);
    expect($array['SalesEmployeeName'])->toBe('John Smith');
    expect($array['CommissionForSalesEmployee'])->toBe(5.5);
    expect($array['Active'])->toBe('tYES');
});

it('excludes null values in toArray', function () {
    $dto = new SalesPersonDto(
        salesEmployeeCode: 1,
        salesEmployeeName: 'John Smith',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('SalesEmployeeCode');
    expect($array)->toHaveKey('SalesEmployeeName');
    expect($array)->not->toHaveKey('CommissionForSalesEmployee');
    expect($array)->not->toHaveKey('CommissionGroup');
});

it('handles locked status', function () {
    $data = [
        'SalesEmployeeCode' => 1,
        'SalesEmployeeName' => 'Inactive User',
        'Locked' => 'tYES',
    ];

    $dto = SalesPersonDto::fromArray($data);

    expect($dto->locked)->toBe(BoYesNo::Yes);
});

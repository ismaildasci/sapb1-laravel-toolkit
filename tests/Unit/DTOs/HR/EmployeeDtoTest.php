<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\HR\EmployeeDto;
use SapB1\Toolkit\Enums\EmployeeStatusType;
use SapB1\Toolkit\Enums\Gender;

it('creates from array', function () {
    $data = [
        'EmployeeID' => 1,
        'LastName' => 'Doe',
        'FirstName' => 'John',
        'MiddleName' => 'William',
        'Gender' => 'gt_Male',
        'JobTitle' => 'Software Developer',
        'Department' => 1,
        'Branch' => 1,
        'eMail' => 'john.doe@example.com',
        'StartDate' => '2024-01-15',
        'StatusCode' => 'etsActive',
    ];

    $dto = EmployeeDto::fromArray($data);

    expect($dto->employeeID)->toBe(1);
    expect($dto->lastName)->toBe('Doe');
    expect($dto->firstName)->toBe('John');
    expect($dto->middleName)->toBe('William');
    expect($dto->gender)->toBe(Gender::Male);
    expect($dto->jobTitle)->toBe('Software Developer');
    expect($dto->statusCode)->toBe(EmployeeStatusType::Active);
});

it('converts to array', function () {
    $dto = new EmployeeDto(
        employeeID: 1,
        lastName: 'Doe',
        firstName: 'John',
        gender: Gender::Male,
        statusCode: EmployeeStatusType::Active,
    );

    $array = $dto->toArray();

    expect($array['EmployeeID'])->toBe(1);
    expect($array['LastName'])->toBe('Doe');
    expect($array['FirstName'])->toBe('John');
    expect($array['Gender'])->toBe('gt_Male');
    expect($array['StatusCode'])->toBe('etsActive');
});

it('excludes null values in toArray', function () {
    $dto = new EmployeeDto(
        employeeID: 1,
        lastName: 'Doe',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('EmployeeID');
    expect($array)->toHaveKey('LastName');
    expect($array)->not->toHaveKey('FirstName');
    expect($array)->not->toHaveKey('Department');
});

it('returns full name correctly', function () {
    $dto = new EmployeeDto(
        firstName: 'John',
        middleName: 'William',
        lastName: 'Doe',
    );

    expect($dto->getFullName())->toBe('John William Doe');
});

it('returns full name without middle name', function () {
    $dto = new EmployeeDto(
        firstName: 'John',
        lastName: 'Doe',
    );

    expect($dto->getFullName())->toBe('John Doe');
});

<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\HR\EmployeeBuilder;
use SapB1\Toolkit\Enums\EmployeeStatusType;
use SapB1\Toolkit\Enums\Gender;

it('builds employee data', function () {
    $builder = EmployeeBuilder::create()
        ->firstName('John')
        ->lastName('Doe')
        ->gender(Gender::Male)
        ->jobTitle('Developer')
        ->department(1)
        ->branch(1)
        ->email('john@example.com')
        ->startDate('2024-01-15')
        ->statusCode(EmployeeStatusType::Active);

    $data = $builder->build();

    expect($data['FirstName'])->toBe('John');
    expect($data['LastName'])->toBe('Doe');
    expect($data['Gender'])->toBe('gt_Male');
    expect($data['JobTitle'])->toBe('Developer');
    expect($data['Department'])->toBe(1);
    expect($data['Branch'])->toBe(1);
    expect($data['eMail'])->toBe('john@example.com');
    expect($data['StatusCode'])->toBe('etsActive');
});

it('can be reset', function () {
    $builder = EmployeeBuilder::create()
        ->firstName('John')
        ->lastName('Doe');

    $builder->reset();
    $data = $builder->build();

    expect($data)->toBeEmpty();
});

it('builds with manager and position', function () {
    $builder = EmployeeBuilder::create()
        ->firstName('John')
        ->lastName('Doe')
        ->manager(5)
        ->position(10);

    $data = $builder->build();

    expect($data['Manager'])->toBe(5);
    expect($data['Position'])->toBe(10);
});

it('builds with termination data', function () {
    $builder = EmployeeBuilder::create()
        ->firstName('John')
        ->lastName('Doe')
        ->statusCode(EmployeeStatusType::Terminated)
        ->terminationDate('2024-12-31')
        ->terminationReason(1);

    $data = $builder->build();

    expect($data['StatusCode'])->toBe('etsTerminated');
    expect($data['TerminationDate'])->toBe('2024-12-31');
    expect($data['TreminationReason'])->toBe(1);
});

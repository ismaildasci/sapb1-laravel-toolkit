<?php

declare(strict_types=1);

use SapB1\Toolkit\Enums\EmployeeStatusType;

it('has correct values', function () {
    expect(EmployeeStatusType::Active->value)->toBe('etsActive');
    expect(EmployeeStatusType::Inactive->value)->toBe('etsInactive');
    expect(EmployeeStatusType::Terminated->value)->toBe('etsTerminated');
});

it('returns correct labels', function () {
    expect(EmployeeStatusType::Active->label())->toBe('Active');
    expect(EmployeeStatusType::Inactive->label())->toBe('Inactive');
    expect(EmployeeStatusType::Terminated->label())->toBe('Terminated');
});

it('can check if active', function () {
    expect(EmployeeStatusType::Active->isActive())->toBeTrue();
    expect(EmployeeStatusType::Inactive->isActive())->toBeFalse();
    expect(EmployeeStatusType::Terminated->isActive())->toBeFalse();
});

it('can be created from value', function () {
    expect(EmployeeStatusType::from('etsActive'))->toBe(EmployeeStatusType::Active);
    expect(EmployeeStatusType::tryFrom('etsInvalid'))->toBeNull();
});

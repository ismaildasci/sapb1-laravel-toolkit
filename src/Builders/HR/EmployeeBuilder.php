<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\HR;

use SapB1\Toolkit\Builders\BaseBuilder;
use SapB1\Toolkit\Enums\EmployeeStatusType;
use SapB1\Toolkit\Enums\Gender;

/**
 * @phpstan-consistent-constructor
 */
final class EmployeeBuilder extends BaseBuilder
{
    public function lastName(string $lastName): static
    {
        return $this->set('LastName', $lastName);
    }

    public function firstName(string $firstName): static
    {
        return $this->set('FirstName', $firstName);
    }

    public function middleName(string $middleName): static
    {
        return $this->set('MiddleName', $middleName);
    }

    public function gender(Gender $gender): static
    {
        return $this->set('Gender', $gender->value);
    }

    public function jobTitle(string $jobTitle): static
    {
        return $this->set('JobTitle', $jobTitle);
    }

    public function employeeType(int $type): static
    {
        return $this->set('EmployeeType', $type);
    }

    public function department(int $department): static
    {
        return $this->set('Department', $department);
    }

    public function branch(int $branch): static
    {
        return $this->set('Branch', $branch);
    }

    public function workStreet(string $street): static
    {
        return $this->set('WorkStreet', $street);
    }

    public function workCity(string $city): static
    {
        return $this->set('WorkCity', $city);
    }

    public function workCountryCode(string $countryCode): static
    {
        return $this->set('WorkCountryCode', $countryCode);
    }

    public function homePhone(string $phone): static
    {
        return $this->set('HomePhone', $phone);
    }

    public function officePhone(string $phone): static
    {
        return $this->set('OfficePhone', $phone);
    }

    public function mobilePhone(string $phone): static
    {
        return $this->set('MobilePhone', $phone);
    }

    public function email(string $email): static
    {
        return $this->set('eMail', $email);
    }

    public function startDate(string $date): static
    {
        return $this->set('StartDate', $date);
    }

    public function statusCode(EmployeeStatusType $status): static
    {
        return $this->set('StatusCode', $status->value);
    }

    public function terminationDate(string $date): static
    {
        return $this->set('TerminationDate', $date);
    }

    public function terminationReason(int $reasonId): static
    {
        return $this->set('TreminationReason', $reasonId);
    }

    public function manager(int $managerId): static
    {
        return $this->set('Manager', $managerId);
    }

    public function position(int $positionId): static
    {
        return $this->set('Position', $positionId);
    }

    public function costCenterCode(string $code): static
    {
        return $this->set('CostCenterCode', $code);
    }

    public function remarks(string $remarks): static
    {
        return $this->set('Remarks', $remarks);
    }

    public function salesPersonCode(int $code): static
    {
        return $this->set('SalesPersonCode', $code);
    }

    public function linkedUser(int $userId): static
    {
        return $this->set('LinkedUser', $userId);
    }

    public function birthDate(string $date): static
    {
        return $this->set('DateOfBirth', $date);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

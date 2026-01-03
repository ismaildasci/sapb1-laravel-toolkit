<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\HR;

use SapB1\Toolkit\DTOs\Base\BaseDto;
use SapB1\Toolkit\Enums\EmployeeStatusType;
use SapB1\Toolkit\Enums\Gender;

/**
 * @phpstan-consistent-constructor
 */
final class EmployeeDto extends BaseDto
{
    public function __construct(
        public readonly ?int $employeeID = null,
        public readonly ?string $lastName = null,
        public readonly ?string $firstName = null,
        public readonly ?string $middleName = null,
        public readonly ?Gender $gender = null,
        public readonly ?string $jobTitle = null,
        public readonly ?int $employeeType = null,
        public readonly ?int $department = null,
        public readonly ?int $branch = null,
        public readonly ?string $workStreet = null,
        public readonly ?string $workCity = null,
        public readonly ?string $workCountryCode = null,
        public readonly ?string $workStateCode = null,
        public readonly ?string $workZipCode = null,
        public readonly ?string $homeStreet = null,
        public readonly ?string $homeCity = null,
        public readonly ?string $homeCountryCode = null,
        public readonly ?string $homePhone = null,
        public readonly ?string $officePhone = null,
        public readonly ?string $mobilePhone = null,
        public readonly ?string $email = null,
        public readonly ?string $startDate = null,
        public readonly ?EmployeeStatusType $statusCode = null,
        public readonly ?string $terminationDate = null,
        public readonly ?int $terminationReason = null,
        public readonly ?string $bankCode = null,
        public readonly ?string $bankBranch = null,
        public readonly ?string $bankAccount = null,
        public readonly ?int $manager = null,
        public readonly ?int $position = null,
        public readonly ?string $costCenterCode = null,
        public readonly ?string $companyNumber = null,
        public readonly ?string $externalEmployeeNumber = null,
        public readonly ?string $passportNumber = null,
        public readonly ?string $passportExpirationDate = null,
        public readonly ?string $pictureBase64 = null,
        public readonly ?string $remarks = null,
        public readonly ?int $salesPersonCode = null,
        public readonly ?int $linkedUser = null,
        public readonly ?string $birthDate = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'employeeID' => $data['EmployeeID'] ?? null,
            'lastName' => $data['LastName'] ?? null,
            'firstName' => $data['FirstName'] ?? null,
            'middleName' => $data['MiddleName'] ?? null,
            'gender' => isset($data['Gender']) ? Gender::tryFrom($data['Gender']) : null,
            'jobTitle' => $data['JobTitle'] ?? null,
            'employeeType' => $data['EmployeeType'] ?? null,
            'department' => $data['Department'] ?? null,
            'branch' => $data['Branch'] ?? null,
            'workStreet' => $data['WorkStreet'] ?? null,
            'workCity' => $data['WorkCity'] ?? null,
            'workCountryCode' => $data['WorkCountryCode'] ?? null,
            'workStateCode' => $data['WorkStateCode'] ?? null,
            'workZipCode' => $data['WorkZipCode'] ?? null,
            'homeStreet' => $data['HomeStreet'] ?? null,
            'homeCity' => $data['HomeCity'] ?? null,
            'homeCountryCode' => $data['HomeCountryCode'] ?? null,
            'homePhone' => $data['HomePhone'] ?? null,
            'officePhone' => $data['OfficePhone'] ?? null,
            'mobilePhone' => $data['MobilePhone'] ?? null,
            'email' => $data['eMail'] ?? null,
            'startDate' => $data['StartDate'] ?? null,
            'statusCode' => isset($data['StatusCode']) ? EmployeeStatusType::tryFrom($data['StatusCode']) : null,
            'terminationDate' => $data['TerminationDate'] ?? null,
            'terminationReason' => $data['TreminationReason'] ?? null,
            'bankCode' => $data['BankCode'] ?? null,
            'bankBranch' => $data['BankBranch'] ?? null,
            'bankAccount' => $data['BankAccount'] ?? null,
            'manager' => $data['Manager'] ?? null,
            'position' => $data['Position'] ?? null,
            'costCenterCode' => $data['CostCenterCode'] ?? null,
            'companyNumber' => $data['CompanyNumber'] ?? null,
            'externalEmployeeNumber' => $data['ExternalEmployeeNumber'] ?? null,
            'passportNumber' => $data['PassportNumber'] ?? null,
            'passportExpirationDate' => $data['PassportExpirationDate'] ?? null,
            'pictureBase64' => $data['Picture'] ?? null,
            'remarks' => $data['Remarks'] ?? null,
            'salesPersonCode' => $data['SalesPersonCode'] ?? null,
            'linkedUser' => $data['LinkedUser'] ?? null,
            'birthDate' => $data['DateOfBirth'] ?? null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'EmployeeID' => $this->employeeID,
            'LastName' => $this->lastName,
            'FirstName' => $this->firstName,
            'MiddleName' => $this->middleName,
            'Gender' => $this->gender?->value,
            'JobTitle' => $this->jobTitle,
            'EmployeeType' => $this->employeeType,
            'Department' => $this->department,
            'Branch' => $this->branch,
            'WorkStreet' => $this->workStreet,
            'WorkCity' => $this->workCity,
            'WorkCountryCode' => $this->workCountryCode,
            'WorkStateCode' => $this->workStateCode,
            'WorkZipCode' => $this->workZipCode,
            'HomeStreet' => $this->homeStreet,
            'HomeCity' => $this->homeCity,
            'HomeCountryCode' => $this->homeCountryCode,
            'HomePhone' => $this->homePhone,
            'OfficePhone' => $this->officePhone,
            'MobilePhone' => $this->mobilePhone,
            'eMail' => $this->email,
            'StartDate' => $this->startDate,
            'StatusCode' => $this->statusCode?->value,
            'TerminationDate' => $this->terminationDate,
            'TreminationReason' => $this->terminationReason,
            'BankCode' => $this->bankCode,
            'BankBranch' => $this->bankBranch,
            'BankAccount' => $this->bankAccount,
            'Manager' => $this->manager,
            'Position' => $this->position,
            'CostCenterCode' => $this->costCenterCode,
            'CompanyNumber' => $this->companyNumber,
            'ExternalEmployeeNumber' => $this->externalEmployeeNumber,
            'PassportNumber' => $this->passportNumber,
            'PassportExpirationDate' => $this->passportExpirationDate,
            'Picture' => $this->pictureBase64,
            'Remarks' => $this->remarks,
            'SalesPersonCode' => $this->salesPersonCode,
            'LinkedUser' => $this->linkedUser,
            'DateOfBirth' => $this->birthDate,
        ], fn ($value) => $value !== null);
    }

    public function getFullName(): string
    {
        return trim(implode(' ', array_filter([
            $this->firstName,
            $this->middleName,
            $this->lastName,
        ])));
    }
}

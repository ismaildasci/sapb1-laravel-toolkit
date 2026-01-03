<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\BusinessPartner;

use SapB1\Toolkit\DTOs\Base\BaseDto;
use SapB1\Toolkit\Enums\BoYesNo;

/**
 * @phpstan-consistent-constructor
 */
final class ContactPersonDto extends BaseDto
{
    public function __construct(
        public readonly ?int $internalCode = null,
        public readonly ?string $cardCode = null,
        public readonly ?string $name = null,
        public readonly ?string $position = null,
        public readonly ?string $address = null,
        public readonly ?string $phone1 = null,
        public readonly ?string $phone2 = null,
        public readonly ?string $mobilePhone = null,
        public readonly ?string $fax = null,
        public readonly ?string $email = null,
        public readonly ?string $remarks1 = null,
        public readonly ?string $remarks2 = null,
        public readonly ?string $password = null,
        public readonly ?string $firstName = null,
        public readonly ?string $middleName = null,
        public readonly ?string $lastName = null,
        public readonly ?string $title = null,
        public readonly ?string $profession = null,
        public readonly ?string $gender = null,
        public readonly ?string $dateOfBirth = null,
        public readonly ?BoYesNo $active = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'internalCode' => isset($data['InternalCode']) ? (int) $data['InternalCode'] : null,
            'cardCode' => $data['CardCode'] ?? null,
            'name' => $data['Name'] ?? null,
            'position' => $data['Position'] ?? null,
            'address' => $data['Address'] ?? null,
            'phone1' => $data['Phone1'] ?? null,
            'phone2' => $data['Phone2'] ?? null,
            'mobilePhone' => $data['MobilePhone'] ?? null,
            'fax' => $data['Fax'] ?? null,
            'email' => $data['E_Mail'] ?? null,
            'remarks1' => $data['Remarks1'] ?? null,
            'remarks2' => $data['Remarks2'] ?? null,
            'password' => $data['Password'] ?? null,
            'firstName' => $data['FirstName'] ?? null,
            'middleName' => $data['MiddleName'] ?? null,
            'lastName' => $data['LastName'] ?? null,
            'title' => $data['Title'] ?? null,
            'profession' => $data['Profession'] ?? null,
            'gender' => $data['Gender'] ?? null,
            'dateOfBirth' => $data['DateOfBirth'] ?? null,
            'active' => isset($data['Active']) ? BoYesNo::tryFrom($data['Active']) : null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'InternalCode' => $this->internalCode,
            'CardCode' => $this->cardCode,
            'Name' => $this->name,
            'Position' => $this->position,
            'Address' => $this->address,
            'Phone1' => $this->phone1,
            'Phone2' => $this->phone2,
            'MobilePhone' => $this->mobilePhone,
            'Fax' => $this->fax,
            'E_Mail' => $this->email,
            'Remarks1' => $this->remarks1,
            'Remarks2' => $this->remarks2,
            'Password' => $this->password,
            'FirstName' => $this->firstName,
            'MiddleName' => $this->middleName,
            'LastName' => $this->lastName,
            'Title' => $this->title,
            'Profession' => $this->profession,
            'Gender' => $this->gender,
            'DateOfBirth' => $this->dateOfBirth,
            'Active' => $this->active?->value,
        ], fn ($value) => $value !== null);
    }
}

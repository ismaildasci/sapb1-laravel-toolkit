<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\BusinessPartner;

use SapB1\Toolkit\Builders\BaseBuilder;
use SapB1\Toolkit\Enums\BoYesNo;

/**
 * Contact Employee builder for standalone contact creation.
 *
 * @phpstan-consistent-constructor
 */
final class ContactBuilder extends BaseBuilder
{
    public function cardCode(string $cardCode): static
    {
        return $this->set('CardCode', $cardCode);
    }

    public function name(string $name): static
    {
        return $this->set('Name', $name);
    }

    public function firstName(string $firstName): static
    {
        return $this->set('FirstName', $firstName);
    }

    public function middleName(string $middleName): static
    {
        return $this->set('MiddleName', $middleName);
    }

    public function lastName(string $lastName): static
    {
        return $this->set('LastName', $lastName);
    }

    public function title(string $title): static
    {
        return $this->set('Title', $title);
    }

    public function position(string $position): static
    {
        return $this->set('Position', $position);
    }

    public function email(string $email): static
    {
        return $this->set('E_Mail', $email);
    }

    public function phone1(string $phone): static
    {
        return $this->set('Phone1', $phone);
    }

    public function phone2(string $phone): static
    {
        return $this->set('Phone2', $phone);
    }

    public function mobilePhone(string $phone): static
    {
        return $this->set('MobilePhone', $phone);
    }

    public function fax(string $fax): static
    {
        return $this->set('Fax', $fax);
    }

    public function address(string $address): static
    {
        return $this->set('Address', $address);
    }

    public function remarks1(string $remarks): static
    {
        return $this->set('Remarks1', $remarks);
    }

    public function remarks2(string $remarks): static
    {
        return $this->set('Remarks2', $remarks);
    }

    public function active(BoYesNo $active): static
    {
        return $this->set('Active', $active->value);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Admin;

use SapB1\Toolkit\Builders\BaseBuilder;
use SapB1\Toolkit\Enums\BoYesNo;
use SapB1\Toolkit\Enums\UserType;

/**
 * @phpstan-consistent-constructor
 */
final class UserBuilder extends BaseBuilder
{
    public function userCode(string $code): static
    {
        return $this->set('UserCode', $code);
    }

    public function userName(string $name): static
    {
        return $this->set('UserName', $name);
    }

    public function superuser(BoYesNo $superuser): static
    {
        return $this->set('Superuser', $superuser->value);
    }

    public function email(string $email): static
    {
        return $this->set('eMail', $email);
    }

    public function mobilePhone(string $phone): static
    {
        return $this->set('MobilePhoneNumber', $phone);
    }

    public function faxNumber(string $fax): static
    {
        return $this->set('FaxNumber', $fax);
    }

    public function branch(int $branch): static
    {
        return $this->set('Branch', $branch);
    }

    public function department(int $department): static
    {
        return $this->set('Department', $department);
    }

    public function locked(BoYesNo $locked): static
    {
        return $this->set('Locked', $locked->value);
    }

    public function userType(UserType $type): static
    {
        return $this->set('UserType', $type->value);
    }

    public function group(int $group): static
    {
        return $this->set('Group', $group);
    }

    public function password(string $password): static
    {
        return $this->set('UserPassword', $password);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

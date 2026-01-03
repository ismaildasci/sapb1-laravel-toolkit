<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\BusinessPartner;

use SapB1\Toolkit\Builders\BaseBuilder;
use SapB1\Toolkit\Enums\BoYesNo;

/**
 * @phpstan-consistent-constructor
 */
final class SalesPersonBuilder extends BaseBuilder
{
    public function salesEmployeeCode(int $code): static
    {
        return $this->set('SalesEmployeeCode', $code);
    }

    public function salesEmployeeName(string $name): static
    {
        return $this->set('SalesEmployeeName', $name);
    }

    public function commissionForSalesEmployee(float $commission): static
    {
        return $this->set('CommissionForSalesEmployee', $commission);
    }

    public function commissionGroup(int $group): static
    {
        return $this->set('CommissionGroup', $group);
    }

    public function locked(BoYesNo $locked): static
    {
        return $this->set('Locked', $locked->value);
    }

    public function employeeID(int $id): static
    {
        return $this->set('EmployeeID', $id);
    }

    public function active(BoYesNo $active): static
    {
        return $this->set('Active', $active->value);
    }

    public function telephone(string $telephone): static
    {
        return $this->set('Telephone', $telephone);
    }

    public function mobile(string $mobile): static
    {
        return $this->set('Mobile', $mobile);
    }

    public function email(string $email): static
    {
        return $this->set('E_Mail', $email);
    }

    public function fax(string $fax): static
    {
        return $this->set('Fax', $fax);
    }

    public function remarks(string $remarks): static
    {
        return $this->set('Remarks', $remarks);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

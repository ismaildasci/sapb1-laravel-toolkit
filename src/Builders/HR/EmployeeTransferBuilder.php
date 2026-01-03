<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\HR;

use SapB1\Toolkit\Builders\BaseBuilder;

/**
 * @phpstan-consistent-constructor
 */
final class EmployeeTransferBuilder extends BaseBuilder
{
    public function transferDate(string $date): static
    {
        return $this->set('TransferDate', $date);
    }

    public function employeeID(int $employeeId): static
    {
        return $this->set('EmployeeID', $employeeId);
    }

    public function fromDepartment(int $departmentId): static
    {
        return $this->set('FromDepartment', $departmentId);
    }

    public function toDepartment(int $departmentId): static
    {
        return $this->set('ToDepartment', $departmentId);
    }

    public function fromBranch(int $branchId): static
    {
        return $this->set('FromBranch', $branchId);
    }

    public function toBranch(int $branchId): static
    {
        return $this->set('ToBranch', $branchId);
    }

    public function fromPosition(int $positionId): static
    {
        return $this->set('FromPosition', $positionId);
    }

    public function toPosition(int $positionId): static
    {
        return $this->set('ToPosition', $positionId);
    }

    public function fromManager(int $managerId): static
    {
        return $this->set('FromManager', $managerId);
    }

    public function toManager(int $managerId): static
    {
        return $this->set('ToManager', $managerId);
    }

    public function comment(string $comment): static
    {
        return $this->set('Comment', $comment);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

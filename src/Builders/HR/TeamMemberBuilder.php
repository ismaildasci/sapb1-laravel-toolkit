<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\HR;

use SapB1\Toolkit\Builders\BaseBuilder;

/**
 * @phpstan-consistent-constructor
 */
final class TeamMemberBuilder extends BaseBuilder
{
    public function employeeID(int $employeeId): static
    {
        return $this->set('EmployeeID', $employeeId);
    }

    public function roleName(string $roleName): static
    {
        return $this->set('RoleName', $roleName);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\HR;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class TeamMemberDto extends BaseDto
{
    public function __construct(
        public readonly ?int $teamID = null,
        public readonly ?int $employeeID = null,
        public readonly ?string $roleName = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'teamID' => $data['TeamID'] ?? null,
            'employeeID' => $data['EmployeeID'] ?? null,
            'roleName' => $data['RoleName'] ?? null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'TeamID' => $this->teamID,
            'EmployeeID' => $this->employeeID,
            'RoleName' => $this->roleName,
        ], fn ($value) => $value !== null);
    }
}

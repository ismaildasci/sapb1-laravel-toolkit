<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\HR;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class EmployeeRoleDto extends BaseDto
{
    public function __construct(
        public readonly ?int $typeID = null,
        public readonly ?string $name = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'typeID' => $data['TypeID'] ?? null,
            'name' => $data['Name'] ?? null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'TypeID' => $this->typeID,
            'Name' => $this->name,
        ], fn ($value) => $value !== null);
    }
}

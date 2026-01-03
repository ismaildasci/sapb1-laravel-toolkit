<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Admin;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class QueryCategoryDto extends BaseDto
{
    public function __construct(
        public readonly ?int $code = null,
        public readonly ?string $name = null,
        public readonly ?int $permissions = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'code' => $data['Code'] ?? null,
            'name' => $data['Name'] ?? null,
            'permissions' => $data['Permissions'] ?? null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'Code' => $this->code,
            'Name' => $this->name,
            'Permissions' => $this->permissions,
        ], fn ($value) => $value !== null);
    }
}

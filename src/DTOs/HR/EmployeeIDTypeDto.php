<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\HR;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class EmployeeIDTypeDto extends BaseDto
{
    public function __construct(
        public readonly ?string $idType = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'idType' => $data['IDType'] ?? null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'IDType' => $this->idType,
        ], fn ($value) => $value !== null);
    }
}

<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Service;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class ServiceGroupDto extends BaseDto
{
    public function __construct(
        public readonly ?int $absEntry = null,
        public readonly ?string $serviceGroupCode = null,
        public readonly ?string $description = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'absEntry' => $data['AbsEntry'] ?? null,
            'serviceGroupCode' => $data['ServiceGroupCode'] ?? null,
            'description' => $data['Description'] ?? null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'AbsEntry' => $this->absEntry,
            'ServiceGroupCode' => $this->serviceGroupCode,
            'Description' => $this->description,
        ], fn ($value) => $value !== null);
    }
}

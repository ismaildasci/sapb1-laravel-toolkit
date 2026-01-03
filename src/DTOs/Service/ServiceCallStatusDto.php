<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Service;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class ServiceCallStatusDto extends BaseDto
{
    public function __construct(
        public readonly ?int $statusId = null,
        public readonly ?string $name = null,
        public readonly ?string $description = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'statusId' => $data['StatusId'] ?? null,
            'name' => $data['Name'] ?? null,
            'description' => $data['Description'] ?? null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'StatusId' => $this->statusId,
            'Name' => $this->name,
            'Description' => $this->description,
        ], fn ($value) => $value !== null);
    }
}

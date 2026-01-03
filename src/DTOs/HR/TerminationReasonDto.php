<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\HR;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class TerminationReasonDto extends BaseDto
{
    public function __construct(
        public readonly ?int $reasonID = null,
        public readonly ?string $name = null,
        public readonly ?string $description = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'reasonID' => $data['ReasonID'] ?? null,
            'name' => $data['Name'] ?? null,
            'description' => $data['Description'] ?? null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'ReasonID' => $this->reasonID,
            'Name' => $this->name,
            'Description' => $this->description,
        ], fn ($value) => $value !== null);
    }
}

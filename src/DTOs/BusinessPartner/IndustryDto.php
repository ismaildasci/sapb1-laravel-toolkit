<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\BusinessPartner;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class IndustryDto extends BaseDto
{
    public function __construct(
        public readonly ?int $industryCode = null,
        public readonly ?string $industryDescription = null,
        public readonly ?string $industryName = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'industryCode' => $data['IndustryCode'] ?? null,
            'industryDescription' => $data['IndustryDescription'] ?? null,
            'industryName' => $data['IndustryName'] ?? null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'IndustryCode' => $this->industryCode,
            'IndustryDescription' => $this->industryDescription,
            'IndustryName' => $this->industryName,
        ], fn ($value) => $value !== null);
    }
}

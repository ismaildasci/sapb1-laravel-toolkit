<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Production;

use SapB1\Toolkit\DTOs\Base\BaseDto;
use SapB1\Toolkit\Enums\ResourceType;

/**
 * @phpstan-consistent-constructor
 */
final class ResourceGroupDto extends BaseDto
{
    public function __construct(
        public readonly ?int $code = null,
        public readonly ?string $name = null,
        public readonly ?ResourceType $type = null,
        public readonly ?float $costName1 = null,
        public readonly ?float $costName2 = null,
        public readonly ?float $costName3 = null,
        public readonly ?float $costName4 = null,
        public readonly ?float $costName5 = null,
        public readonly ?float $costName6 = null,
        public readonly ?float $costName7 = null,
        public readonly ?float $costName8 = null,
        public readonly ?float $costName9 = null,
        public readonly ?float $costName10 = null,
        public readonly ?int $numOfUnitsText = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'code' => $data['Code'] ?? null,
            'name' => $data['Name'] ?? null,
            'type' => isset($data['Type']) ? ResourceType::tryFrom($data['Type']) : null,
            'costName1' => isset($data['CostName1']) ? (float) $data['CostName1'] : null,
            'costName2' => isset($data['CostName2']) ? (float) $data['CostName2'] : null,
            'costName3' => isset($data['CostName3']) ? (float) $data['CostName3'] : null,
            'costName4' => isset($data['CostName4']) ? (float) $data['CostName4'] : null,
            'costName5' => isset($data['CostName5']) ? (float) $data['CostName5'] : null,
            'costName6' => isset($data['CostName6']) ? (float) $data['CostName6'] : null,
            'costName7' => isset($data['CostName7']) ? (float) $data['CostName7'] : null,
            'costName8' => isset($data['CostName8']) ? (float) $data['CostName8'] : null,
            'costName9' => isset($data['CostName9']) ? (float) $data['CostName9'] : null,
            'costName10' => isset($data['CostName10']) ? (float) $data['CostName10'] : null,
            'numOfUnitsText' => $data['NumOfUnitsText'] ?? null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'Code' => $this->code,
            'Name' => $this->name,
            'Type' => $this->type?->value,
            'CostName1' => $this->costName1,
            'CostName2' => $this->costName2,
            'CostName3' => $this->costName3,
            'CostName4' => $this->costName4,
            'CostName5' => $this->costName5,
            'CostName6' => $this->costName6,
            'CostName7' => $this->costName7,
            'CostName8' => $this->costName8,
            'CostName9' => $this->costName9,
            'CostName10' => $this->costName10,
            'NumOfUnitsText' => $this->numOfUnitsText,
        ], fn ($value) => $value !== null);
    }
}

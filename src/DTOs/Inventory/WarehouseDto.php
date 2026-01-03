<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Inventory;

use SapB1\Toolkit\DTOs\Base\BaseDto;
use SapB1\Toolkit\Enums\BoYesNo;

/**
 * @phpstan-consistent-constructor
 */
final class WarehouseDto extends BaseDto
{
    public function __construct(
        public readonly ?string $warehouseCode = null,
        public readonly ?string $warehouseName = null,
        public readonly ?string $street = null,
        public readonly ?string $block = null,
        public readonly ?string $zipCode = null,
        public readonly ?string $city = null,
        public readonly ?string $county = null,
        public readonly ?string $country = null,
        public readonly ?string $state = null,
        public readonly ?BoYesNo $inactive = null,
        public readonly ?string $branchCode = null,
        public readonly ?string $defaultBin = null,
        public readonly ?BoYesNo $binActivated = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'warehouseCode' => $data['WarehouseCode'] ?? null,
            'warehouseName' => $data['WarehouseName'] ?? null,
            'street' => $data['Street'] ?? null,
            'block' => $data['Block'] ?? null,
            'zipCode' => $data['ZipCode'] ?? null,
            'city' => $data['City'] ?? null,
            'county' => $data['County'] ?? null,
            'country' => $data['Country'] ?? null,
            'state' => $data['State'] ?? null,
            'inactive' => isset($data['Inactive']) ? BoYesNo::tryFrom($data['Inactive']) : null,
            'branchCode' => isset($data['BranchCode']) ? (string) $data['BranchCode'] : null,
            'defaultBin' => isset($data['DefaultBin']) ? (string) $data['DefaultBin'] : null,
            'binActivated' => isset($data['BinActivat']) ? BoYesNo::tryFrom($data['BinActivat']) : null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'WarehouseCode' => $this->warehouseCode,
            'WarehouseName' => $this->warehouseName,
            'Street' => $this->street,
            'Block' => $this->block,
            'ZipCode' => $this->zipCode,
            'City' => $this->city,
            'County' => $this->county,
            'Country' => $this->country,
            'State' => $this->state,
            'Inactive' => $this->inactive?->value,
            'BranchCode' => $this->branchCode,
            'DefaultBin' => $this->defaultBin,
            'BinActivat' => $this->binActivated?->value,
        ], fn ($value) => $value !== null);
    }
}

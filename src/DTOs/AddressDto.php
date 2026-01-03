<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class AddressDto extends BaseDto
{
    public function __construct(
        public readonly ?string $addressName = null,
        public readonly ?string $street = null,
        public readonly ?string $block = null,
        public readonly ?string $zipCode = null,
        public readonly ?string $city = null,
        public readonly ?string $county = null,
        public readonly ?string $country = null,
        public readonly ?string $state = null,
        public readonly ?string $buildingFloorRoom = null,
        public readonly ?string $addressType = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'addressName' => $data['AddressName'] ?? null,
            'street' => $data['Street'] ?? null,
            'block' => $data['Block'] ?? null,
            'zipCode' => $data['ZipCode'] ?? null,
            'city' => $data['City'] ?? null,
            'county' => $data['County'] ?? null,
            'country' => $data['Country'] ?? null,
            'state' => $data['State'] ?? null,
            'buildingFloorRoom' => $data['BuildingFloorRoom'] ?? null,
            'addressType' => $data['AddressType'] ?? null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'AddressName' => $this->addressName,
            'Street' => $this->street,
            'Block' => $this->block,
            'ZipCode' => $this->zipCode,
            'City' => $this->city,
            'County' => $this->county,
            'Country' => $this->country,
            'State' => $this->state,
            'BuildingFloorRoom' => $this->buildingFloorRoom,
            'AddressType' => $this->addressType,
        ], fn ($value) => $value !== null);
    }
}

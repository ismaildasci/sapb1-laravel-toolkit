<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\BusinessPartner;

use SapB1\Toolkit\DTOs\Base\BaseDto;
use SapB1\Toolkit\Enums\BoYesNo;

/**
 * @phpstan-consistent-constructor
 */
final class TerritoryDto extends BaseDto
{
    public function __construct(
        public readonly ?int $territoryID = null,
        public readonly ?string $description = null,
        public readonly ?int $locationIndex = null,
        public readonly ?BoYesNo $inactive = null,
        public readonly ?int $parent = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        $inactive = null;
        if (isset($data['Inactive'])) {
            $inactive = BoYesNo::tryFrom($data['Inactive']);
        }

        return [
            'territoryID' => $data['TerritoryID'] ?? null,
            'description' => $data['Description'] ?? null,
            'locationIndex' => $data['LocationIndex'] ?? null,
            'inactive' => $inactive,
            'parent' => $data['Parent'] ?? null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'TerritoryID' => $this->territoryID,
            'Description' => $this->description,
            'LocationIndex' => $this->locationIndex,
            'Inactive' => $this->inactive?->value,
            'Parent' => $this->parent,
        ], fn ($value) => $value !== null);
    }
}

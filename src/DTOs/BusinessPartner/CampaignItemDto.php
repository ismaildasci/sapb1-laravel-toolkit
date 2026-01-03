<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\BusinessPartner;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class CampaignItemDto extends BaseDto
{
    public function __construct(
        public readonly ?int $campaignNumber = null,
        public readonly ?int $lineNumber = null,
        public readonly ?string $itemCode = null,
        public readonly ?string $itemName = null,
        public readonly ?string $itemType = null,
        public readonly ?string $itemGroup = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'campaignNumber' => $data['CampaignNumber'] ?? null,
            'lineNumber' => $data['LineNumber'] ?? null,
            'itemCode' => $data['ItemCode'] ?? null,
            'itemName' => $data['ItemName'] ?? null,
            'itemType' => $data['ItemType'] ?? null,
            'itemGroup' => $data['ItemGroup'] ?? null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'CampaignNumber' => $this->campaignNumber,
            'LineNumber' => $this->lineNumber,
            'ItemCode' => $this->itemCode,
            'ItemName' => $this->itemName,
            'ItemType' => $this->itemType,
            'ItemGroup' => $this->itemGroup,
        ], fn ($value) => $value !== null);
    }
}

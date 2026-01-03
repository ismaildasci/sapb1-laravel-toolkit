<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\BusinessPartner;

use SapB1\Toolkit\DTOs\Base\BaseDto;
use SapB1\Toolkit\Enums\BoYesNo;

/**
 * @phpstan-consistent-constructor
 */
final class CampaignResponseTypeDto extends BaseDto
{
    public function __construct(
        public readonly ?string $responseType = null,
        public readonly ?string $responseTypeDescription = null,
        public readonly ?BoYesNo $isActive = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        $isActive = null;
        if (isset($data['IsActive'])) {
            $isActive = BoYesNo::tryFrom($data['IsActive']);
        }

        return [
            'responseType' => $data['ResponseType'] ?? null,
            'responseTypeDescription' => $data['ResponseTypeDescription'] ?? null,
            'isActive' => $isActive,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'ResponseType' => $this->responseType,
            'ResponseTypeDescription' => $this->responseTypeDescription,
            'IsActive' => $this->isActive?->value,
        ], fn ($value) => $value !== null);
    }
}

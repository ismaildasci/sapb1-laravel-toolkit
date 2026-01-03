<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\BusinessPartner;

use SapB1\Toolkit\DTOs\Base\BaseDto;
use SapB1\Toolkit\Enums\CardType;

/**
 * @phpstan-consistent-constructor
 */
final class BusinessPartnerGroupDto extends BaseDto
{
    public function __construct(
        public readonly ?int $code = null,
        public readonly ?string $name = null,
        public readonly ?CardType $type = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        $type = null;
        if (isset($data['Type'])) {
            $type = CardType::tryFrom($data['Type']);
        }

        return [
            'code' => $data['Code'] ?? null,
            'name' => $data['Name'] ?? null,
            'type' => $type,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'Code' => $this->code,
            'Name' => $this->name,
            'Type' => $this->type?->value,
        ], fn ($value) => $value !== null);
    }
}

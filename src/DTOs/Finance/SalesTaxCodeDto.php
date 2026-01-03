<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Finance;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class SalesTaxCodeDto extends BaseDto
{
    public function __construct(
        public readonly ?string $code = null,
        public readonly ?string $name = null,
        public readonly ?string $userSignature = null,
        public readonly ?string $rate = null,
        public readonly ?string $effective = null,
        public readonly ?string $taxableAmount = null,
        public readonly ?string $isActive = null,
        public readonly ?string $taxType = null,
        public readonly ?string $freight = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'code' => $data['Code'] ?? null,
            'name' => $data['Name'] ?? null,
            'userSignature' => $data['UserSignature'] ?? null,
            'rate' => $data['Rate'] ?? null,
            'effective' => $data['Effective'] ?? null,
            'taxableAmount' => $data['TaxableAmount'] ?? null,
            'isActive' => $data['IsActive'] ?? null,
            'taxType' => $data['TaxType'] ?? null,
            'freight' => $data['Freight'] ?? null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'Code' => $this->code,
            'Name' => $this->name,
            'UserSignature' => $this->userSignature,
            'Rate' => $this->rate,
            'Effective' => $this->effective,
            'TaxableAmount' => $this->taxableAmount,
            'IsActive' => $this->isActive,
            'TaxType' => $this->taxType,
            'Freight' => $this->freight,
        ], fn ($value) => $value !== null);
    }
}

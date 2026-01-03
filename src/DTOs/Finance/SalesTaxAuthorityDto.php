<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Finance;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class SalesTaxAuthorityDto extends BaseDto
{
    public function __construct(
        public readonly ?int $code = null,
        public readonly ?string $name = null,
        public readonly ?string $userSignature = null,
        public readonly ?string $taxAccount = null,
        public readonly ?string $taxType = null,
        public readonly ?string $taxDefinition = null,
        public readonly ?string $businessPartner = null,
        public readonly ?string $dataExportCode = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'code' => isset($data['Code']) ? (int) $data['Code'] : null,
            'name' => $data['Name'] ?? null,
            'userSignature' => $data['UserSignature'] ?? null,
            'taxAccount' => $data['TaxAccount'] ?? null,
            'taxType' => $data['TaxType'] ?? null,
            'taxDefinition' => $data['TaxDefinition'] ?? null,
            'businessPartner' => $data['BusinessPartner'] ?? null,
            'dataExportCode' => $data['DataExportCode'] ?? null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'Code' => $this->code,
            'Name' => $this->name,
            'UserSignature' => $this->userSignature,
            'TaxAccount' => $this->taxAccount,
            'TaxType' => $this->taxType,
            'TaxDefinition' => $this->taxDefinition,
            'BusinessPartner' => $this->businessPartner,
            'DataExportCode' => $this->dataExportCode,
        ], fn ($value) => $value !== null);
    }
}

<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Finance;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class CreditCardDto extends BaseDto
{
    public function __construct(
        public readonly ?int $creditCardCode = null,
        public readonly ?string $creditCardName = null,
        public readonly ?string $gLAccount = null,
        public readonly ?string $telephone = null,
        public readonly ?string $companyId = null,
        public readonly ?string $countryCode = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'creditCardCode' => isset($data['CreditCardCode']) ? (int) $data['CreditCardCode'] : null,
            'creditCardName' => $data['CreditCardName'] ?? null,
            'gLAccount' => $data['GLAccount'] ?? null,
            'telephone' => $data['Telephone'] ?? null,
            'companyId' => $data['CompanyId'] ?? null,
            'countryCode' => $data['CountryCode'] ?? null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'CreditCardCode' => $this->creditCardCode,
            'CreditCardName' => $this->creditCardName,
            'GLAccount' => $this->gLAccount,
            'Telephone' => $this->telephone,
            'CompanyId' => $this->companyId,
            'CountryCode' => $this->countryCode,
        ], fn ($value) => $value !== null);
    }
}

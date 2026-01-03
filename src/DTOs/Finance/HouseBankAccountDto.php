<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Finance;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class HouseBankAccountDto extends BaseDto
{
    public function __construct(
        public readonly ?int $absoluteEntry = null,
        public readonly ?string $bankCode = null,
        public readonly ?string $accNo = null,
        public readonly ?string $branch = null,
        public readonly ?string $street = null,
        public readonly ?string $block = null,
        public readonly ?string $zipCode = null,
        public readonly ?string $city = null,
        public readonly ?string $county = null,
        public readonly ?string $country = null,
        public readonly ?string $state = null,
        public readonly ?string $bIIK = null,
        public readonly ?string $accountName = null,
        public readonly ?string $controlKey = null,
        public readonly ?string $glAccount = null,
        public readonly ?string $glInterimAccount = null,
        public readonly ?string $isClosed = null,
        public readonly ?string $ibanOfHouseBankAccount = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'absoluteEntry' => isset($data['AbsoluteEntry']) ? (int) $data['AbsoluteEntry'] : null,
            'bankCode' => $data['BankCode'] ?? null,
            'accNo' => $data['AccNo'] ?? null,
            'branch' => $data['Branch'] ?? null,
            'street' => $data['Street'] ?? null,
            'block' => $data['Block'] ?? null,
            'zipCode' => $data['ZipCode'] ?? null,
            'city' => $data['City'] ?? null,
            'county' => $data['County'] ?? null,
            'country' => $data['Country'] ?? null,
            'state' => $data['State'] ?? null,
            'bIIK' => $data['BIIK'] ?? null,
            'accountName' => $data['AccountName'] ?? null,
            'controlKey' => $data['ControlKey'] ?? null,
            'glAccount' => $data['GLAccount'] ?? null,
            'glInterimAccount' => $data['GLInterimAccount'] ?? null,
            'isClosed' => $data['IsClosed'] ?? null,
            'ibanOfHouseBankAccount' => $data['IbanOfHouseBankAccount'] ?? null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'AbsoluteEntry' => $this->absoluteEntry,
            'BankCode' => $this->bankCode,
            'AccNo' => $this->accNo,
            'Branch' => $this->branch,
            'Street' => $this->street,
            'Block' => $this->block,
            'ZipCode' => $this->zipCode,
            'City' => $this->city,
            'County' => $this->county,
            'Country' => $this->country,
            'State' => $this->state,
            'BIIK' => $this->bIIK,
            'AccountName' => $this->accountName,
            'ControlKey' => $this->controlKey,
            'GLAccount' => $this->glAccount,
            'GLInterimAccount' => $this->glInterimAccount,
            'IsClosed' => $this->isClosed,
            'IbanOfHouseBankAccount' => $this->ibanOfHouseBankAccount,
        ], fn ($value) => $value !== null);
    }
}

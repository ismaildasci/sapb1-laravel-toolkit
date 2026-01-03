<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\BusinessPartner;

use SapB1\Toolkit\DTOs\AddressDto;
use SapB1\Toolkit\DTOs\Base\BaseDto;
use SapB1\Toolkit\Enums\BoYesNo;
use SapB1\Toolkit\Enums\CardType;

/**
 * @phpstan-consistent-constructor
 */
final class BusinessPartnerDto extends BaseDto
{
    /**
     * @param  array<ContactPersonDto>  $contactPersons
     * @param  array<AddressDto>  $addresses
     */
    public function __construct(
        public readonly ?string $cardCode = null,
        public readonly ?string $cardName = null,
        public readonly ?string $cardForeignName = null,
        public readonly ?CardType $cardType = null,
        public readonly ?string $groupCode = null,
        public readonly ?string $federalTaxId = null,
        public readonly ?string $currency = null,
        public readonly ?string $phone1 = null,
        public readonly ?string $phone2 = null,
        public readonly ?string $cellular = null,
        public readonly ?string $fax = null,
        public readonly ?string $email = null,
        public readonly ?string $website = null,
        public readonly ?string $contactPerson = null,
        public readonly ?string $notes = null,
        public readonly ?string $payTermsGrpCode = null,
        public readonly ?string $priceListNum = null,
        public readonly ?float $creditLimit = null,
        public readonly ?float $maxCommitment = null,
        public readonly ?float $currentAccountBalance = null,
        public readonly ?float $openDeliveryNotesBalance = null,
        public readonly ?float $openOrdersBalance = null,
        public readonly ?BoYesNo $valid = null,
        public readonly ?BoYesNo $frozen = null,
        public readonly ?string $salesPersonCode = null,
        public readonly ?string $territory = null,
        public readonly ?string $industry = null,
        public readonly ?string $companyPrivate = null,
        public readonly ?string $aliasName = null,
        public readonly ?string $defaultBranch = null,
        public readonly ?string $defaultBankCode = null,
        public readonly array $contactPersons = [],
        public readonly array $addresses = [],
    ) {}

    protected static function mapFromArray(array $data): array
    {
        $contacts = [];
        if (isset($data['ContactEmployees']) && is_array($data['ContactEmployees'])) {
            foreach ($data['ContactEmployees'] as $contact) {
                $contacts[] = ContactPersonDto::fromArray($contact);
            }
        }

        $addresses = [];
        if (isset($data['BPAddresses']) && is_array($data['BPAddresses'])) {
            foreach ($data['BPAddresses'] as $address) {
                $addresses[] = AddressDto::fromArray($address);
            }
        }

        return [
            'cardCode' => $data['CardCode'] ?? null,
            'cardName' => $data['CardName'] ?? null,
            'cardForeignName' => $data['CardForeignName'] ?? null,
            'cardType' => isset($data['CardType']) ? CardType::tryFrom($data['CardType']) : null,
            'groupCode' => isset($data['GroupCode']) ? (string) $data['GroupCode'] : null,
            'federalTaxId' => $data['FederalTaxID'] ?? null,
            'currency' => $data['Currency'] ?? null,
            'phone1' => $data['Phone1'] ?? null,
            'phone2' => $data['Phone2'] ?? null,
            'cellular' => $data['Cellular'] ?? null,
            'fax' => $data['Fax'] ?? null,
            'email' => $data['EmailAddress'] ?? null,
            'website' => $data['Website'] ?? null,
            'contactPerson' => $data['ContactPerson'] ?? null,
            'notes' => $data['Notes'] ?? null,
            'payTermsGrpCode' => isset($data['PayTermsGrpCode']) ? (string) $data['PayTermsGrpCode'] : null,
            'priceListNum' => isset($data['PriceListNum']) ? (string) $data['PriceListNum'] : null,
            'creditLimit' => isset($data['CreditLimit']) ? (float) $data['CreditLimit'] : null,
            'maxCommitment' => isset($data['MaxCommitment']) ? (float) $data['MaxCommitment'] : null,
            'currentAccountBalance' => isset($data['CurrentAccountBalance']) ? (float) $data['CurrentAccountBalance'] : null,
            'openDeliveryNotesBalance' => isset($data['OpenDeliveryNotesBalance']) ? (float) $data['OpenDeliveryNotesBalance'] : null,
            'openOrdersBalance' => isset($data['OpenOrdersBalance']) ? (float) $data['OpenOrdersBalance'] : null,
            'valid' => isset($data['Valid']) ? BoYesNo::tryFrom($data['Valid']) : null,
            'frozen' => isset($data['Frozen']) ? BoYesNo::tryFrom($data['Frozen']) : null,
            'salesPersonCode' => isset($data['SalesPersonCode']) ? (string) $data['SalesPersonCode'] : null,
            'territory' => isset($data['Territory']) ? (string) $data['Territory'] : null,
            'industry' => isset($data['Industry']) ? (string) $data['Industry'] : null,
            'companyPrivate' => $data['CompanyPrivate'] ?? null,
            'aliasName' => $data['AliasName'] ?? null,
            'defaultBranch' => isset($data['DefaultBranch']) ? (string) $data['DefaultBranch'] : null,
            'defaultBankCode' => $data['DefaultBankCode'] ?? null,
            'contactPersons' => $contacts,
            'addresses' => $addresses,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = array_filter([
            'CardCode' => $this->cardCode,
            'CardName' => $this->cardName,
            'CardForeignName' => $this->cardForeignName,
            'CardType' => $this->cardType?->value,
            'GroupCode' => $this->groupCode,
            'FederalTaxID' => $this->federalTaxId,
            'Currency' => $this->currency,
            'Phone1' => $this->phone1,
            'Phone2' => $this->phone2,
            'Cellular' => $this->cellular,
            'Fax' => $this->fax,
            'EmailAddress' => $this->email,
            'Website' => $this->website,
            'ContactPerson' => $this->contactPerson,
            'Notes' => $this->notes,
            'PayTermsGrpCode' => $this->payTermsGrpCode,
            'PriceListNum' => $this->priceListNum,
            'CreditLimit' => $this->creditLimit,
            'MaxCommitment' => $this->maxCommitment,
            'CurrentAccountBalance' => $this->currentAccountBalance,
            'OpenDeliveryNotesBalance' => $this->openDeliveryNotesBalance,
            'OpenOrdersBalance' => $this->openOrdersBalance,
            'Valid' => $this->valid?->value,
            'Frozen' => $this->frozen?->value,
            'SalesPersonCode' => $this->salesPersonCode,
            'Territory' => $this->territory,
            'Industry' => $this->industry,
            'CompanyPrivate' => $this->companyPrivate,
            'AliasName' => $this->aliasName,
            'DefaultBranch' => $this->defaultBranch,
            'DefaultBankCode' => $this->defaultBankCode,
        ], fn ($value) => $value !== null);

        if (! empty($this->contactPersons)) {
            $data['ContactEmployees'] = array_map(
                fn (ContactPersonDto $contact) => $contact->toArray(),
                $this->contactPersons
            );
        }

        if (! empty($this->addresses)) {
            $data['BPAddresses'] = array_map(
                fn (AddressDto $address) => $address->toArray(),
                $this->addresses
            );
        }

        return $data;
    }
}

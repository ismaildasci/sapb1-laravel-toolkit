<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\BusinessPartner;

use SapB1\Toolkit\Builders\BaseBuilder;
use SapB1\Toolkit\DTOs\AddressDto;
use SapB1\Toolkit\DTOs\BusinessPartner\ContactPersonDto;
use SapB1\Toolkit\Enums\BoYesNo;
use SapB1\Toolkit\Enums\CardType;

/**
 * Builder for Business Partners.
 *
 * @phpstan-consistent-constructor
 */
final class BusinessPartnerBuilder extends BaseBuilder
{
    public function cardCode(string $code): static
    {
        return $this->set('CardCode', $code);
    }

    public function cardName(string $name): static
    {
        return $this->set('CardName', $name);
    }

    public function cardForeignName(string $name): static
    {
        return $this->set('CardForeignName', $name);
    }

    public function cardType(CardType $type): static
    {
        return $this->set('CardType', $type->value);
    }

    public function groupCode(int $code): static
    {
        return $this->set('GroupCode', $code);
    }

    public function federalTaxId(string $taxId): static
    {
        return $this->set('FederalTaxID', $taxId);
    }

    public function currency(string $currency): static
    {
        return $this->set('Currency', $currency);
    }

    public function phone1(string $phone): static
    {
        return $this->set('Phone1', $phone);
    }

    public function phone2(string $phone): static
    {
        return $this->set('Phone2', $phone);
    }

    public function cellular(string $phone): static
    {
        return $this->set('Cellular', $phone);
    }

    public function fax(string $fax): static
    {
        return $this->set('Fax', $fax);
    }

    public function email(string $email): static
    {
        return $this->set('EmailAddress', $email);
    }

    public function website(string $website): static
    {
        return $this->set('Website', $website);
    }

    public function contactPerson(string $contactPerson): static
    {
        return $this->set('ContactPerson', $contactPerson);
    }

    public function notes(string $notes): static
    {
        return $this->set('Notes', $notes);
    }

    public function payTermsGrpCode(int $code): static
    {
        return $this->set('PayTermsGrpCode', $code);
    }

    public function priceListNum(int $priceListNum): static
    {
        return $this->set('PriceListNum', $priceListNum);
    }

    public function creditLimit(float $limit): static
    {
        return $this->set('CreditLimit', $limit);
    }

    public function maxCommitment(float $commitment): static
    {
        return $this->set('MaxCommitment', $commitment);
    }

    public function valid(BoYesNo $value): static
    {
        return $this->set('Valid', $value->value);
    }

    public function frozen(BoYesNo $value): static
    {
        return $this->set('Frozen', $value->value);
    }

    public function salesPersonCode(int $code): static
    {
        return $this->set('SalesPersonCode', $code);
    }

    public function territory(int $territory): static
    {
        return $this->set('Territory', $territory);
    }

    public function industry(int $industry): static
    {
        return $this->set('Industry', $industry);
    }

    public function aliasName(string $alias): static
    {
        return $this->set('AliasName', $alias);
    }

    /**
     * @param  array<ContactPersonDto|array<string, mixed>>  $contacts
     */
    public function contactEmployees(array $contacts): static
    {
        $mappedContacts = array_map(
            fn ($contact) => $contact instanceof ContactPersonDto ? $contact->toArray() : $contact,
            $contacts
        );

        return $this->set('ContactEmployees', $mappedContacts);
    }

    /**
     * @param  ContactPersonDto|array<string, mixed>  $contact
     */
    public function addContact(ContactPersonDto|array $contact): static
    {
        $contacts = $this->get('ContactEmployees', []);
        $contacts[] = $contact instanceof ContactPersonDto ? $contact->toArray() : $contact;

        return $this->set('ContactEmployees', $contacts);
    }

    /**
     * @param  array<AddressDto|array<string, mixed>>  $addresses
     */
    public function bpAddresses(array $addresses): static
    {
        $mappedAddresses = array_map(
            fn ($address) => $address instanceof AddressDto ? $address->toArray() : $address,
            $addresses
        );

        return $this->set('BPAddresses', $mappedAddresses);
    }

    /**
     * @param  AddressDto|array<string, mixed>  $address
     */
    public function addAddress(AddressDto|array $address): static
    {
        $addresses = $this->get('BPAddresses', []);
        $addresses[] = $address instanceof AddressDto ? $address->toArray() : $address;

        return $this->set('BPAddresses', $addresses);
    }

    /**
     * @return array<string, mixed>
     */
    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

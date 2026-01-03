<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\BusinessPartner;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\BusinessPartner\BusinessPartnerBuilder;
use SapB1\Toolkit\DTOs\BusinessPartner\BusinessPartnerDto;
use SapB1\Toolkit\Enums\CardType;

/**
 * Business Partner actions.
 */
final class BusinessPartnerAction extends BaseAction
{
    protected string $entity = 'BusinessPartners';

    /**
     * @param  string|BusinessPartnerBuilder|array<string, mixed>  ...$args
     */
    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        $first = $args[0];

        if (is_string($first)) {
            return $this->find($first);
        }

        return $this->create($first);
    }

    /**
     * Find a business partner by CardCode.
     */
    public function find(string $cardCode): BusinessPartnerDto
    {
        $data = $this->client()
            ->service($this->entity)
            ->find($cardCode);

        return BusinessPartnerDto::fromResponse($data);
    }

    /**
     * Create a new business partner.
     *
     * @param  BusinessPartnerBuilder|array<string, mixed>  $data
     */
    public function create(BusinessPartnerBuilder|array $data): BusinessPartnerDto
    {
        $payload = $data instanceof BusinessPartnerBuilder ? $data->build() : $data;
        $response = $this->client()
            ->service($this->entity)
            ->create($payload);

        return BusinessPartnerDto::fromResponse($response);
    }

    /**
     * Update an existing business partner.
     *
     * @param  BusinessPartnerBuilder|array<string, mixed>  $data
     */
    public function update(string $cardCode, BusinessPartnerBuilder|array $data): BusinessPartnerDto
    {
        $payload = $data instanceof BusinessPartnerBuilder ? $data->build() : $data;
        $response = $this->client()
            ->service($this->entity)
            ->update($cardCode, $payload);

        return BusinessPartnerDto::fromResponse($response);
    }

    /**
     * Delete a business partner.
     */
    public function delete(string $cardCode): bool
    {
        $this->client()
            ->service($this->entity)
            ->delete($cardCode);

        return true;
    }

    /**
     * Get all customers.
     *
     * @return array<BusinessPartnerDto>
     */
    public function getCustomers(): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("CardType eq '".CardType::Customer->value."'")
            ->get();

        return array_map(
            fn (array $item) => BusinessPartnerDto::fromResponse($item),
            $response['value'] ?? []
        );
    }

    /**
     * Get all vendors.
     *
     * @return array<BusinessPartnerDto>
     */
    public function getVendors(): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("CardType eq '".CardType::Supplier->value."'")
            ->get();

        return array_map(
            fn (array $item) => BusinessPartnerDto::fromResponse($item),
            $response['value'] ?? []
        );
    }

    /**
     * Get all leads.
     *
     * @return array<BusinessPartnerDto>
     */
    public function getLeads(): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("CardType eq '".CardType::Lead->value."'")
            ->get();

        return array_map(
            fn (array $item) => BusinessPartnerDto::fromResponse($item),
            $response['value'] ?? []
        );
    }

    /**
     * Search business partners by name.
     *
     * @return array<BusinessPartnerDto>
     */
    public function search(string $query): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("contains(CardName, '{$query}')")
            ->get();

        return array_map(
            fn (array $item) => BusinessPartnerDto::fromResponse($item),
            $response['value'] ?? []
        );
    }

    /**
     * Get active business partners.
     *
     * @return array<BusinessPartnerDto>
     */
    public function getActive(): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("Valid eq 'tYES' and Frozen eq 'tNO'")
            ->get();

        return array_map(
            fn (array $item) => BusinessPartnerDto::fromResponse($item),
            $response['value'] ?? []
        );
    }
}

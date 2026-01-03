<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\BusinessPartner;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\BusinessPartner\BusinessPartnerGroupBuilder;
use SapB1\Toolkit\DTOs\BusinessPartner\BusinessPartnerGroupDto;
use SapB1\Toolkit\Enums\CardType;

/**
 * Business Partner Group actions.
 */
final class BusinessPartnerGroupAction extends BaseAction
{
    protected string $entity = 'BusinessPartnerGroups';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $code): BusinessPartnerGroupDto
    {
        $data = $this->client()->service($this->entity)->find($code);

        return BusinessPartnerGroupDto::fromResponse($data);
    }

    /**
     * @param  BusinessPartnerGroupBuilder|array<string, mixed>  $data
     */
    public function create(BusinessPartnerGroupBuilder|array $data): BusinessPartnerGroupDto
    {
        $payload = $data instanceof BusinessPartnerGroupBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return BusinessPartnerGroupDto::fromResponse($response);
    }

    /**
     * @param  BusinessPartnerGroupBuilder|array<string, mixed>  $data
     */
    public function update(int $code, BusinessPartnerGroupBuilder|array $data): BusinessPartnerGroupDto
    {
        $payload = $data instanceof BusinessPartnerGroupBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($code, $payload);

        return BusinessPartnerGroupDto::fromResponse($response);
    }

    public function delete(int $code): bool
    {
        $this->client()->service($this->entity)->delete($code);

        return true;
    }

    /**
     * @return array<BusinessPartnerGroupDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => BusinessPartnerGroupDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Get groups by type (Customer, Vendor, Lead).
     *
     * @return array<BusinessPartnerGroupDto>
     */
    public function getByType(CardType $type): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("Type eq '{$type->value}'")
            ->get();

        return array_map(fn (array $item) => BusinessPartnerGroupDto::fromResponse($item), $response['value'] ?? []);
    }
}

<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\BusinessPartner;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\BusinessPartner\SalesOpportunityBuilder;
use SapB1\Toolkit\DTOs\BusinessPartner\SalesOpportunityDto;
use SapB1\Toolkit\Enums\OpportunityStatus;

/**
 * Sales Opportunity actions.
 */
final class SalesOpportunityAction extends BaseAction
{
    protected string $entity = 'SalesOpportunities';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $sequentialNo): SalesOpportunityDto
    {
        $data = $this->client()->service($this->entity)->find($sequentialNo);

        return SalesOpportunityDto::fromResponse($data);
    }

    /**
     * @param  SalesOpportunityBuilder|array<string, mixed>  $data
     */
    public function create(SalesOpportunityBuilder|array $data): SalesOpportunityDto
    {
        $payload = $data instanceof SalesOpportunityBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return SalesOpportunityDto::fromResponse($response);
    }

    /**
     * @param  SalesOpportunityBuilder|array<string, mixed>  $data
     */
    public function update(int $sequentialNo, SalesOpportunityBuilder|array $data): SalesOpportunityDto
    {
        $payload = $data instanceof SalesOpportunityBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($sequentialNo, $payload);

        return SalesOpportunityDto::fromResponse($response);
    }

    public function delete(int $sequentialNo): bool
    {
        $this->client()->service($this->entity)->delete($sequentialNo);

        return true;
    }

    /**
     * Close an opportunity.
     */
    public function close(int $sequentialNo): bool
    {
        $this->client()->service($this->entity)->action($sequentialNo, 'Close');

        return true;
    }

    /**
     * @return array<SalesOpportunityDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => SalesOpportunityDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Get open opportunities.
     *
     * @return array<SalesOpportunityDto>
     */
    public function getOpen(): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("Status eq 'sos_Open'")
            ->get();

        return array_map(fn (array $item) => SalesOpportunityDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Get opportunities by customer.
     *
     * @return array<SalesOpportunityDto>
     */
    public function getByCardCode(string $cardCode): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("CardCode eq '{$cardCode}'")
            ->get();

        return array_map(fn (array $item) => SalesOpportunityDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Get opportunities by status.
     *
     * @return array<SalesOpportunityDto>
     */
    public function getByStatus(OpportunityStatus $status): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("Status eq '{$status->value}'")
            ->get();

        return array_map(fn (array $item) => SalesOpportunityDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Get opportunities by sales person.
     *
     * @return array<SalesOpportunityDto>
     */
    public function getBySalesPerson(int $salesPerson): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("SalesPerson eq {$salesPerson}")
            ->get();

        return array_map(fn (array $item) => SalesOpportunityDto::fromResponse($item), $response['value'] ?? []);
    }
}

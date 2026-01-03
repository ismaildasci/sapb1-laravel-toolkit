<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Service;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Service\ServiceContractBuilder;
use SapB1\Toolkit\DTOs\Service\ServiceContractDto;

/**
 * Service Contract actions.
 */
final class ServiceContractAction extends BaseAction
{
    protected string $entity = 'ServiceContracts';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $contractID): ServiceContractDto
    {
        $data = $this->client()->service($this->entity)->find($contractID);

        return ServiceContractDto::fromResponse($data);
    }

    /**
     * @param  ServiceContractBuilder|array<string, mixed>  $data
     */
    public function create(ServiceContractBuilder|array $data): ServiceContractDto
    {
        $payload = $data instanceof ServiceContractBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return ServiceContractDto::fromResponse($response);
    }

    /**
     * @param  ServiceContractBuilder|array<string, mixed>  $data
     */
    public function update(int $contractID, ServiceContractBuilder|array $data): ServiceContractDto
    {
        $payload = $data instanceof ServiceContractBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($contractID, $payload);

        return ServiceContractDto::fromResponse($response);
    }

    public function delete(int $contractID): bool
    {
        $this->client()->service($this->entity)->delete($contractID);

        return true;
    }

    /**
     * Close a service contract.
     */
    public function close(int $contractID): bool
    {
        $this->client()->service($this->entity)->action($contractID, 'Close');

        return true;
    }

    /**
     * Cancel a service contract.
     */
    public function cancel(int $contractID): bool
    {
        $this->client()->service($this->entity)->action($contractID, 'Cancel');

        return true;
    }

    /**
     * @return array<ServiceContractDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => ServiceContractDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Get active contracts.
     *
     * @return array<ServiceContractDto>
     */
    public function getActive(): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("Status eq 'scs_Active'")
            ->get();

        return array_map(fn (array $item) => ServiceContractDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Get contracts by customer.
     *
     * @return array<ServiceContractDto>
     */
    public function getByCustomer(string $cardCode): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("CustomerCode eq '{$cardCode}'")
            ->get();

        return array_map(fn (array $item) => ServiceContractDto::fromResponse($item), $response['value'] ?? []);
    }
}

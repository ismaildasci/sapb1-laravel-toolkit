<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Service;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Service\ServiceCallBuilder;
use SapB1\Toolkit\DTOs\Service\ServiceCallDto;
use SapB1\Toolkit\Enums\ServiceCallPriority;

/**
 * Service Call actions.
 */
final class ServiceCallAction extends BaseAction
{
    protected string $entity = 'ServiceCalls';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $serviceCallID): ServiceCallDto
    {
        $data = $this->client()->service($this->entity)->find($serviceCallID);

        return ServiceCallDto::fromResponse($data);
    }

    /**
     * @param  ServiceCallBuilder|array<string, mixed>  $data
     */
    public function create(ServiceCallBuilder|array $data): ServiceCallDto
    {
        $payload = $data instanceof ServiceCallBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return ServiceCallDto::fromResponse($response);
    }

    /**
     * @param  ServiceCallBuilder|array<string, mixed>  $data
     */
    public function update(int $serviceCallID, ServiceCallBuilder|array $data): ServiceCallDto
    {
        $payload = $data instanceof ServiceCallBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($serviceCallID, $payload);

        return ServiceCallDto::fromResponse($response);
    }

    public function delete(int $serviceCallID): bool
    {
        $this->client()->service($this->entity)->delete($serviceCallID);

        return true;
    }

    /**
     * Close a service call.
     */
    public function close(int $serviceCallID): bool
    {
        $this->client()->service($this->entity)->action($serviceCallID, 'Close');

        return true;
    }

    /**
     * @return array<ServiceCallDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => ServiceCallDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Get open service calls.
     *
     * @return array<ServiceCallDto>
     */
    public function getOpen(): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter('Status eq -1')
            ->get();

        return array_map(fn (array $item) => ServiceCallDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Get service calls by customer.
     *
     * @return array<ServiceCallDto>
     */
    public function getByCustomer(string $cardCode): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("CustomerCode eq '{$cardCode}'")
            ->get();

        return array_map(fn (array $item) => ServiceCallDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Get service calls by technician.
     *
     * @return array<ServiceCallDto>
     */
    public function getByTechnician(int $technicianCode): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("TechnicianCode eq {$technicianCode}")
            ->get();

        return array_map(fn (array $item) => ServiceCallDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Get service calls by priority.
     *
     * @return array<ServiceCallDto>
     */
    public function getByPriority(ServiceCallPriority $priority): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("Priority eq '{$priority->value}'")
            ->get();

        return array_map(fn (array $item) => ServiceCallDto::fromResponse($item), $response['value'] ?? []);
    }
}

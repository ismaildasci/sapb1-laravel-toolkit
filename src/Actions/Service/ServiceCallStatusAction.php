<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Service;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Service\ServiceCallStatusBuilder;
use SapB1\Toolkit\DTOs\Service\ServiceCallStatusDto;

/**
 * Service Call Status actions.
 */
final class ServiceCallStatusAction extends BaseAction
{
    protected string $entity = 'ServiceCallStatus';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $statusId): ServiceCallStatusDto
    {
        $data = $this->client()->service($this->entity)->find($statusId);

        return ServiceCallStatusDto::fromResponse($data);
    }

    /**
     * @param  ServiceCallStatusBuilder|array<string, mixed>  $data
     */
    public function create(ServiceCallStatusBuilder|array $data): ServiceCallStatusDto
    {
        $payload = $data instanceof ServiceCallStatusBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return ServiceCallStatusDto::fromResponse($response);
    }

    /**
     * @param  ServiceCallStatusBuilder|array<string, mixed>  $data
     */
    public function update(int $statusId, ServiceCallStatusBuilder|array $data): ServiceCallStatusDto
    {
        $payload = $data instanceof ServiceCallStatusBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($statusId, $payload);

        return ServiceCallStatusDto::fromResponse($response);
    }

    public function delete(int $statusId): bool
    {
        $this->client()->service($this->entity)->delete($statusId);

        return true;
    }

    /**
     * @return array<ServiceCallStatusDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => ServiceCallStatusDto::fromResponse($item), $response['value'] ?? []);
    }
}

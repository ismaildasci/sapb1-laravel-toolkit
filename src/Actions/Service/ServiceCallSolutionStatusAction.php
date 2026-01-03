<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Service;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Service\ServiceCallSolutionStatusBuilder;
use SapB1\Toolkit\DTOs\Service\ServiceCallSolutionStatusDto;

/**
 * Service Call Solution Status actions.
 */
final class ServiceCallSolutionStatusAction extends BaseAction
{
    protected string $entity = 'ServiceCallSolutionStatus';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $statusId): ServiceCallSolutionStatusDto
    {
        $data = $this->client()->service($this->entity)->find($statusId);

        return ServiceCallSolutionStatusDto::fromResponse($data);
    }

    /**
     * @param  ServiceCallSolutionStatusBuilder|array<string, mixed>  $data
     */
    public function create(ServiceCallSolutionStatusBuilder|array $data): ServiceCallSolutionStatusDto
    {
        $payload = $data instanceof ServiceCallSolutionStatusBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return ServiceCallSolutionStatusDto::fromResponse($response);
    }

    /**
     * @param  ServiceCallSolutionStatusBuilder|array<string, mixed>  $data
     */
    public function update(int $statusId, ServiceCallSolutionStatusBuilder|array $data): ServiceCallSolutionStatusDto
    {
        $payload = $data instanceof ServiceCallSolutionStatusBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($statusId, $payload);

        return ServiceCallSolutionStatusDto::fromResponse($response);
    }

    public function delete(int $statusId): bool
    {
        $this->client()->service($this->entity)->delete($statusId);

        return true;
    }

    /**
     * @return array<ServiceCallSolutionStatusDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => ServiceCallSolutionStatusDto::fromResponse($item), $response['value'] ?? []);
    }
}

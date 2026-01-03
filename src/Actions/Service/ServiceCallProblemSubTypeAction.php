<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Service;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Service\ServiceCallProblemSubTypeBuilder;
use SapB1\Toolkit\DTOs\Service\ServiceCallProblemSubTypeDto;

/**
 * Service Call Problem Sub Type actions.
 */
final class ServiceCallProblemSubTypeAction extends BaseAction
{
    protected string $entity = 'ServiceCallProblemSubTypes';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $problemSubTypeID): ServiceCallProblemSubTypeDto
    {
        $data = $this->client()->service($this->entity)->find($problemSubTypeID);

        return ServiceCallProblemSubTypeDto::fromResponse($data);
    }

    /**
     * @param  ServiceCallProblemSubTypeBuilder|array<string, mixed>  $data
     */
    public function create(ServiceCallProblemSubTypeBuilder|array $data): ServiceCallProblemSubTypeDto
    {
        $payload = $data instanceof ServiceCallProblemSubTypeBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return ServiceCallProblemSubTypeDto::fromResponse($response);
    }

    /**
     * @param  ServiceCallProblemSubTypeBuilder|array<string, mixed>  $data
     */
    public function update(int $problemSubTypeID, ServiceCallProblemSubTypeBuilder|array $data): ServiceCallProblemSubTypeDto
    {
        $payload = $data instanceof ServiceCallProblemSubTypeBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($problemSubTypeID, $payload);

        return ServiceCallProblemSubTypeDto::fromResponse($response);
    }

    public function delete(int $problemSubTypeID): bool
    {
        $this->client()->service($this->entity)->delete($problemSubTypeID);

        return true;
    }

    /**
     * @return array<ServiceCallProblemSubTypeDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => ServiceCallProblemSubTypeDto::fromResponse($item), $response['value'] ?? []);
    }
}

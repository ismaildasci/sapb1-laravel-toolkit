<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Service;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Service\ServiceCallProblemTypeBuilder;
use SapB1\Toolkit\DTOs\Service\ServiceCallProblemTypeDto;

/**
 * Service Call Problem Type actions.
 */
final class ServiceCallProblemTypeAction extends BaseAction
{
    protected string $entity = 'ServiceCallProblemTypes';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $problemTypeID): ServiceCallProblemTypeDto
    {
        $data = $this->client()->service($this->entity)->find($problemTypeID);

        return ServiceCallProblemTypeDto::fromResponse($data);
    }

    /**
     * @param  ServiceCallProblemTypeBuilder|array<string, mixed>  $data
     */
    public function create(ServiceCallProblemTypeBuilder|array $data): ServiceCallProblemTypeDto
    {
        $payload = $data instanceof ServiceCallProblemTypeBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return ServiceCallProblemTypeDto::fromResponse($response);
    }

    /**
     * @param  ServiceCallProblemTypeBuilder|array<string, mixed>  $data
     */
    public function update(int $problemTypeID, ServiceCallProblemTypeBuilder|array $data): ServiceCallProblemTypeDto
    {
        $payload = $data instanceof ServiceCallProblemTypeBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($problemTypeID, $payload);

        return ServiceCallProblemTypeDto::fromResponse($response);
    }

    public function delete(int $problemTypeID): bool
    {
        $this->client()->service($this->entity)->delete($problemTypeID);

        return true;
    }

    /**
     * @return array<ServiceCallProblemTypeDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => ServiceCallProblemTypeDto::fromResponse($item), $response['value'] ?? []);
    }
}

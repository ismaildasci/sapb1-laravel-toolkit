<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Service;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Service\ServiceCallOriginBuilder;
use SapB1\Toolkit\DTOs\Service\ServiceCallOriginDto;

/**
 * Service Call Origin actions.
 */
final class ServiceCallOriginAction extends BaseAction
{
    protected string $entity = 'ServiceCallOrigins';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $originID): ServiceCallOriginDto
    {
        $data = $this->client()->service($this->entity)->find($originID);

        return ServiceCallOriginDto::fromResponse($data);
    }

    /**
     * @param  ServiceCallOriginBuilder|array<string, mixed>  $data
     */
    public function create(ServiceCallOriginBuilder|array $data): ServiceCallOriginDto
    {
        $payload = $data instanceof ServiceCallOriginBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return ServiceCallOriginDto::fromResponse($response);
    }

    /**
     * @param  ServiceCallOriginBuilder|array<string, mixed>  $data
     */
    public function update(int $originID, ServiceCallOriginBuilder|array $data): ServiceCallOriginDto
    {
        $payload = $data instanceof ServiceCallOriginBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($originID, $payload);

        return ServiceCallOriginDto::fromResponse($response);
    }

    public function delete(int $originID): bool
    {
        $this->client()->service($this->entity)->delete($originID);

        return true;
    }

    /**
     * @return array<ServiceCallOriginDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => ServiceCallOriginDto::fromResponse($item), $response['value'] ?? []);
    }
}

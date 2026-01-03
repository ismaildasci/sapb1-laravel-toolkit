<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Service;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Service\ServiceCallTypeBuilder;
use SapB1\Toolkit\DTOs\Service\ServiceCallTypeDto;

/**
 * Service Call Type actions.
 */
final class ServiceCallTypeAction extends BaseAction
{
    protected string $entity = 'ServiceCallTypes';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $callTypeID): ServiceCallTypeDto
    {
        $data = $this->client()->service($this->entity)->find($callTypeID);

        return ServiceCallTypeDto::fromResponse($data);
    }

    /**
     * @param  ServiceCallTypeBuilder|array<string, mixed>  $data
     */
    public function create(ServiceCallTypeBuilder|array $data): ServiceCallTypeDto
    {
        $payload = $data instanceof ServiceCallTypeBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return ServiceCallTypeDto::fromResponse($response);
    }

    /**
     * @param  ServiceCallTypeBuilder|array<string, mixed>  $data
     */
    public function update(int $callTypeID, ServiceCallTypeBuilder|array $data): ServiceCallTypeDto
    {
        $payload = $data instanceof ServiceCallTypeBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($callTypeID, $payload);

        return ServiceCallTypeDto::fromResponse($response);
    }

    public function delete(int $callTypeID): bool
    {
        $this->client()->service($this->entity)->delete($callTypeID);

        return true;
    }

    /**
     * @return array<ServiceCallTypeDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => ServiceCallTypeDto::fromResponse($item), $response['value'] ?? []);
    }
}

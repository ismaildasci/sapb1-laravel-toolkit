<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Service;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Service\ServiceGroupBuilder;
use SapB1\Toolkit\DTOs\Service\ServiceGroupDto;

/**
 * Service Group actions.
 */
final class ServiceGroupAction extends BaseAction
{
    protected string $entity = 'ServiceGroups';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $absEntry): ServiceGroupDto
    {
        $data = $this->client()->service($this->entity)->find($absEntry);

        return ServiceGroupDto::fromResponse($data);
    }

    /**
     * @param  ServiceGroupBuilder|array<string, mixed>  $data
     */
    public function create(ServiceGroupBuilder|array $data): ServiceGroupDto
    {
        $payload = $data instanceof ServiceGroupBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return ServiceGroupDto::fromResponse($response);
    }

    /**
     * @param  ServiceGroupBuilder|array<string, mixed>  $data
     */
    public function update(int $absEntry, ServiceGroupBuilder|array $data): ServiceGroupDto
    {
        $payload = $data instanceof ServiceGroupBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($absEntry, $payload);

        return ServiceGroupDto::fromResponse($response);
    }

    public function delete(int $absEntry): bool
    {
        $this->client()->service($this->entity)->delete($absEntry);

        return true;
    }

    /**
     * @return array<ServiceGroupDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => ServiceGroupDto::fromResponse($item), $response['value'] ?? []);
    }
}

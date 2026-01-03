<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Production;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Production\ResourceCapacityBuilder;
use SapB1\Toolkit\DTOs\Production\ResourceCapacityDto;

/**
 * Resource Capacity actions.
 */
final class ResourceCapacityAction extends BaseAction
{
    protected string $entity = 'ResourceCapacities';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $id): ResourceCapacityDto
    {
        $data = $this->client()->service($this->entity)->find($id);

        return ResourceCapacityDto::fromResponse($data);
    }

    /**
     * @param  ResourceCapacityBuilder|array<string, mixed>  $data
     */
    public function create(ResourceCapacityBuilder|array $data): ResourceCapacityDto
    {
        $payload = $data instanceof ResourceCapacityBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return ResourceCapacityDto::fromResponse($response);
    }

    /**
     * @param  ResourceCapacityBuilder|array<string, mixed>  $data
     */
    public function update(int $id, ResourceCapacityBuilder|array $data): ResourceCapacityDto
    {
        $payload = $data instanceof ResourceCapacityBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($id, $payload);

        return ResourceCapacityDto::fromResponse($response);
    }

    public function delete(int $id): bool
    {
        $this->client()->service($this->entity)->delete($id);

        return true;
    }

    /**
     * @return array<ResourceCapacityDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => ResourceCapacityDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Get capacities by resource code.
     *
     * @return array<ResourceCapacityDto>
     */
    public function getByResource(string $resourceCode): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("Code eq '{$resourceCode}'")
            ->get();

        return array_map(fn (array $item) => ResourceCapacityDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Get capacities by warehouse.
     *
     * @return array<ResourceCapacityDto>
     */
    public function getByWarehouse(string $warehouse): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("Warehouse eq '{$warehouse}'")
            ->get();

        return array_map(fn (array $item) => ResourceCapacityDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Get capacities by date.
     *
     * @return array<ResourceCapacityDto>
     */
    public function getByDate(string $date): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("Date eq '{$date}'")
            ->get();

        return array_map(fn (array $item) => ResourceCapacityDto::fromResponse($item), $response['value'] ?? []);
    }
}

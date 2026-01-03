<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Production;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Production\ResourceGroupBuilder;
use SapB1\Toolkit\DTOs\Production\ResourceGroupDto;
use SapB1\Toolkit\Enums\ResourceType;

/**
 * Resource Group actions.
 */
final class ResourceGroupAction extends BaseAction
{
    protected string $entity = 'ResourceGroups';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $code): ResourceGroupDto
    {
        $data = $this->client()->service($this->entity)->find($code);

        return ResourceGroupDto::fromResponse($data);
    }

    /**
     * @param  ResourceGroupBuilder|array<string, mixed>  $data
     */
    public function create(ResourceGroupBuilder|array $data): ResourceGroupDto
    {
        $payload = $data instanceof ResourceGroupBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return ResourceGroupDto::fromResponse($response);
    }

    /**
     * @param  ResourceGroupBuilder|array<string, mixed>  $data
     */
    public function update(int $code, ResourceGroupBuilder|array $data): ResourceGroupDto
    {
        $payload = $data instanceof ResourceGroupBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($code, $payload);

        return ResourceGroupDto::fromResponse($response);
    }

    public function delete(int $code): bool
    {
        $this->client()->service($this->entity)->delete($code);

        return true;
    }

    /**
     * @return array<ResourceGroupDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => ResourceGroupDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Get groups by type.
     *
     * @return array<ResourceGroupDto>
     */
    public function getByType(ResourceType $type): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("Type eq '{$type->value}'")
            ->get();

        return array_map(fn (array $item) => ResourceGroupDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Get machine groups.
     *
     * @return array<ResourceGroupDto>
     */
    public function getMachineGroups(): array
    {
        return $this->getByType(ResourceType::Machine);
    }

    /**
     * Get labor groups.
     *
     * @return array<ResourceGroupDto>
     */
    public function getLaborGroups(): array
    {
        return $this->getByType(ResourceType::Labor);
    }
}

<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Production;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Production\ResourceBuilder;
use SapB1\Toolkit\DTOs\Production\ResourceDto;
use SapB1\Toolkit\Enums\BoYesNo;
use SapB1\Toolkit\Enums\ResourceType;

/**
 * Resource actions.
 */
final class ResourceAction extends BaseAction
{
    protected string $entity = 'Resources';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_string($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(string $code): ResourceDto
    {
        $data = $this->client()->service($this->entity)->find($code);

        return ResourceDto::fromResponse($data);
    }

    /**
     * @param  ResourceBuilder|array<string, mixed>  $data
     */
    public function create(ResourceBuilder|array $data): ResourceDto
    {
        $payload = $data instanceof ResourceBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return ResourceDto::fromResponse($response);
    }

    /**
     * @param  ResourceBuilder|array<string, mixed>  $data
     */
    public function update(string $code, ResourceBuilder|array $data): ResourceDto
    {
        $payload = $data instanceof ResourceBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($code, $payload);

        return ResourceDto::fromResponse($response);
    }

    public function delete(string $code): bool
    {
        $this->client()->service($this->entity)->delete($code);

        return true;
    }

    /**
     * @return array<ResourceDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => ResourceDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Get active resources.
     *
     * @return array<ResourceDto>
     */
    public function getActive(): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("Active eq '".BoYesNo::Yes->value."'")
            ->get();

        return array_map(fn (array $item) => ResourceDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Get resources by type.
     *
     * @return array<ResourceDto>
     */
    public function getByType(ResourceType $type): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("Type eq '{$type->value}'")
            ->get();

        return array_map(fn (array $item) => ResourceDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Get machine resources.
     *
     * @return array<ResourceDto>
     */
    public function getMachines(): array
    {
        return $this->getByType(ResourceType::Machine);
    }

    /**
     * Get labor resources.
     *
     * @return array<ResourceDto>
     */
    public function getLabor(): array
    {
        return $this->getByType(ResourceType::Labor);
    }

    /**
     * Get resources by group.
     *
     * @return array<ResourceDto>
     */
    public function getByGroup(int $groupCode): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("Group eq {$groupCode}")
            ->get();

        return array_map(fn (array $item) => ResourceDto::fromResponse($item), $response['value'] ?? []);
    }
}

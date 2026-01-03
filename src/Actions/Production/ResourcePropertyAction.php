<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Production;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Production\ResourcePropertyBuilder;
use SapB1\Toolkit\DTOs\Production\ResourcePropertyDto;

/**
 * Resource Property actions.
 */
final class ResourcePropertyAction extends BaseAction
{
    protected string $entity = 'ResourceProperties';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $code): ResourcePropertyDto
    {
        $data = $this->client()->service($this->entity)->find($code);

        return ResourcePropertyDto::fromResponse($data);
    }

    /**
     * @param  ResourcePropertyBuilder|array<string, mixed>  $data
     */
    public function create(ResourcePropertyBuilder|array $data): ResourcePropertyDto
    {
        $payload = $data instanceof ResourcePropertyBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return ResourcePropertyDto::fromResponse($response);
    }

    /**
     * @param  ResourcePropertyBuilder|array<string, mixed>  $data
     */
    public function update(int $code, ResourcePropertyBuilder|array $data): ResourcePropertyDto
    {
        $payload = $data instanceof ResourcePropertyBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($code, $payload);

        return ResourcePropertyDto::fromResponse($response);
    }

    /**
     * @return array<ResourcePropertyDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => ResourcePropertyDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Search properties by name.
     *
     * @return array<ResourcePropertyDto>
     */
    public function search(string $name): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("contains(Name, '{$name}')")
            ->get();

        return array_map(fn (array $item) => ResourcePropertyDto::fromResponse($item), $response['value'] ?? []);
    }
}

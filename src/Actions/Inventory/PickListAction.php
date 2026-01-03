<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Inventory;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Inventory\PickListBuilder;
use SapB1\Toolkit\DTOs\Inventory\PickListDto;

/**
 * Pick List actions.
 */
final class PickListAction extends BaseAction
{
    protected string $entity = 'PickLists';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $absoluteEntry): PickListDto
    {
        $data = $this->client()->service($this->entity)->find($absoluteEntry);

        return PickListDto::fromResponse($data);
    }

    /**
     * @param  PickListBuilder|array<string, mixed>  $data
     */
    public function create(PickListBuilder|array $data): PickListDto
    {
        $payload = $data instanceof PickListBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return PickListDto::fromResponse($response);
    }

    /**
     * @param  PickListBuilder|array<string, mixed>  $data
     */
    public function update(int $absoluteEntry, PickListBuilder|array $data): PickListDto
    {
        $payload = $data instanceof PickListBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($absoluteEntry, $payload);

        return PickListDto::fromResponse($response);
    }

    public function delete(int $absoluteEntry): bool
    {
        $this->client()->service($this->entity)->delete($absoluteEntry);

        return true;
    }

    /**
     * @return array<PickListDto>
     */
    public function getOpen(): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("Status eq 'ps_Released'")
            ->get();

        return array_map(fn (array $item) => PickListDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * @return array<PickListDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => PickListDto::fromResponse($item), $response['value'] ?? []);
    }
}

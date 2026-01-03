<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Inventory;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Inventory\InventoryCycleBuilder;
use SapB1\Toolkit\DTOs\Inventory\InventoryCycleDto;

/**
 * Inventory Cycle actions.
 */
final class InventoryCycleAction extends BaseAction
{
    protected string $entity = 'InventoryCycles';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $cycleCode): InventoryCycleDto
    {
        $data = $this->client()->service($this->entity)->find($cycleCode);

        return InventoryCycleDto::fromResponse($data);
    }

    /**
     * @param  InventoryCycleBuilder|array<string, mixed>  $data
     */
    public function create(InventoryCycleBuilder|array $data): InventoryCycleDto
    {
        $payload = $data instanceof InventoryCycleBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return InventoryCycleDto::fromResponse($response);
    }

    /**
     * @param  InventoryCycleBuilder|array<string, mixed>  $data
     */
    public function update(int $cycleCode, InventoryCycleBuilder|array $data): InventoryCycleDto
    {
        $payload = $data instanceof InventoryCycleBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($cycleCode, $payload);

        return InventoryCycleDto::fromResponse($response);
    }

    public function delete(int $cycleCode): bool
    {
        $this->client()->service($this->entity)->delete($cycleCode);

        return true;
    }

    /**
     * @return array<InventoryCycleDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => InventoryCycleDto::fromResponse($item), $response['value'] ?? []);
    }
}

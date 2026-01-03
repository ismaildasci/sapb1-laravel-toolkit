<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Inventory;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Inventory\CycleCountDeterminationBuilder;
use SapB1\Toolkit\DTOs\Inventory\CycleCountDeterminationDto;

/**
 * Cycle Count Determination actions.
 */
final class CycleCountDeterminationAction extends BaseAction
{
    protected string $entity = 'CycleCountDeterminations';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_string($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(string $warehouseCode): CycleCountDeterminationDto
    {
        $data = $this->client()->service($this->entity)->find($warehouseCode);

        return CycleCountDeterminationDto::fromResponse($data);
    }

    /**
     * @param  CycleCountDeterminationBuilder|array<string, mixed>  $data
     */
    public function create(CycleCountDeterminationBuilder|array $data): CycleCountDeterminationDto
    {
        $payload = $data instanceof CycleCountDeterminationBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return CycleCountDeterminationDto::fromResponse($response);
    }

    /**
     * @param  CycleCountDeterminationBuilder|array<string, mixed>  $data
     */
    public function update(string $warehouseCode, CycleCountDeterminationBuilder|array $data): CycleCountDeterminationDto
    {
        $payload = $data instanceof CycleCountDeterminationBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($warehouseCode, $payload);

        return CycleCountDeterminationDto::fromResponse($response);
    }

    public function delete(string $warehouseCode): bool
    {
        $this->client()->service($this->entity)->delete($warehouseCode);

        return true;
    }

    /**
     * @return array<CycleCountDeterminationDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => CycleCountDeterminationDto::fromResponse($item), $response['value'] ?? []);
    }
}

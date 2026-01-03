<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\BusinessPartner;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\BusinessPartner\TerritoryBuilder;
use SapB1\Toolkit\DTOs\BusinessPartner\TerritoryDto;

/**
 * Territory actions.
 */
final class TerritoryAction extends BaseAction
{
    protected string $entity = 'Territories';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $territoryID): TerritoryDto
    {
        $data = $this->client()->service($this->entity)->find($territoryID);

        return TerritoryDto::fromResponse($data);
    }

    /**
     * @param  TerritoryBuilder|array<string, mixed>  $data
     */
    public function create(TerritoryBuilder|array $data): TerritoryDto
    {
        $payload = $data instanceof TerritoryBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return TerritoryDto::fromResponse($response);
    }

    /**
     * @param  TerritoryBuilder|array<string, mixed>  $data
     */
    public function update(int $territoryID, TerritoryBuilder|array $data): TerritoryDto
    {
        $payload = $data instanceof TerritoryBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($territoryID, $payload);

        return TerritoryDto::fromResponse($response);
    }

    public function delete(int $territoryID): bool
    {
        $this->client()->service($this->entity)->delete($territoryID);

        return true;
    }

    /**
     * @return array<TerritoryDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => TerritoryDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Get active territories.
     *
     * @return array<TerritoryDto>
     */
    public function getActive(): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("Inactive eq 'tNO'")
            ->get();

        return array_map(fn (array $item) => TerritoryDto::fromResponse($item), $response['value'] ?? []);
    }
}

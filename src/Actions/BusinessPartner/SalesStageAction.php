<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\BusinessPartner;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\BusinessPartner\SalesStageBuilder;
use SapB1\Toolkit\DTOs\BusinessPartner\SalesStageDto;

/**
 * Sales Stage actions.
 */
final class SalesStageAction extends BaseAction
{
    protected string $entity = 'SalesStages';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $sequenceNo): SalesStageDto
    {
        $data = $this->client()->service($this->entity)->find($sequenceNo);

        return SalesStageDto::fromResponse($data);
    }

    /**
     * @param  SalesStageBuilder|array<string, mixed>  $data
     */
    public function create(SalesStageBuilder|array $data): SalesStageDto
    {
        $payload = $data instanceof SalesStageBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return SalesStageDto::fromResponse($response);
    }

    /**
     * @param  SalesStageBuilder|array<string, mixed>  $data
     */
    public function update(int $sequenceNo, SalesStageBuilder|array $data): SalesStageDto
    {
        $payload = $data instanceof SalesStageBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($sequenceNo, $payload);

        return SalesStageDto::fromResponse($response);
    }

    public function delete(int $sequenceNo): bool
    {
        $this->client()->service($this->entity)->delete($sequenceNo);

        return true;
    }

    /**
     * @return array<SalesStageDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => SalesStageDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Get active (not cancelled) stages.
     *
     * @return array<SalesStageDto>
     */
    public function getActive(): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("Cancelled eq 'tNO'")
            ->get();

        return array_map(fn (array $item) => SalesStageDto::fromResponse($item), $response['value'] ?? []);
    }
}

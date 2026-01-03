<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Inventory;

use SapB1\Toolkit\Actions\Base\DocumentAction;
use SapB1\Toolkit\Builders\Inventory\StockTakingBuilder;
use SapB1\Toolkit\DTOs\Inventory\StockTakingDto;

/**
 * Stock Taking actions.
 */
final class StockTakingAction extends DocumentAction
{
    protected string $entity = 'StockTakings';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $docEntry): StockTakingDto
    {
        return StockTakingDto::fromResponse($this->getDocument($docEntry));
    }

    /**
     * @param  StockTakingBuilder|array<string, mixed>  $data
     */
    public function create(StockTakingBuilder|array $data): StockTakingDto
    {
        $payload = $data instanceof StockTakingBuilder ? $data->build() : $data;

        return StockTakingDto::fromResponse($this->createDocument($payload));
    }

    /**
     * @param  StockTakingBuilder|array<string, mixed>  $data
     */
    public function update(int $docEntry, StockTakingBuilder|array $data): StockTakingDto
    {
        $payload = $data instanceof StockTakingBuilder ? $data->build() : $data;

        return StockTakingDto::fromResponse($this->updateDocument($docEntry, $payload));
    }

    public function close(int $docEntry): bool
    {
        return $this->closeDocument($docEntry);
    }

    public function cancel(int $docEntry): bool
    {
        return $this->cancelDocument($docEntry);
    }

    /**
     * @return array<StockTakingDto>
     */
    public function getOpen(): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("DocumentStatus eq 'bost_Open'")
            ->get();

        return array_map(fn (array $item) => StockTakingDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * @return array<StockTakingDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => StockTakingDto::fromResponse($item), $response['value'] ?? []);
    }
}

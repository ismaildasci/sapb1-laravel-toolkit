<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Inventory;

use SapB1\Toolkit\Actions\Base\DocumentAction;
use SapB1\Toolkit\Builders\Inventory\InventoryOpeningBalanceBuilder;
use SapB1\Toolkit\DTOs\Inventory\InventoryOpeningBalanceDto;

/**
 * Inventory Opening Balance actions.
 */
final class InventoryOpeningBalanceAction extends DocumentAction
{
    protected string $entity = 'InventoryOpeningBalances';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $docEntry): InventoryOpeningBalanceDto
    {
        return InventoryOpeningBalanceDto::fromResponse($this->getDocument($docEntry));
    }

    /**
     * @param  InventoryOpeningBalanceBuilder|array<string, mixed>  $data
     */
    public function create(InventoryOpeningBalanceBuilder|array $data): InventoryOpeningBalanceDto
    {
        $payload = $data instanceof InventoryOpeningBalanceBuilder ? $data->build() : $data;

        return InventoryOpeningBalanceDto::fromResponse($this->createDocument($payload));
    }

    /**
     * @param  InventoryOpeningBalanceBuilder|array<string, mixed>  $data
     */
    public function update(int $docEntry, InventoryOpeningBalanceBuilder|array $data): InventoryOpeningBalanceDto
    {
        $payload = $data instanceof InventoryOpeningBalanceBuilder ? $data->build() : $data;

        return InventoryOpeningBalanceDto::fromResponse($this->updateDocument($docEntry, $payload));
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
     * @return array<InventoryOpeningBalanceDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => InventoryOpeningBalanceDto::fromResponse($item), $response['value'] ?? []);
    }
}

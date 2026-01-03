<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Admin;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Admin\SpecialPriceBuilder;
use SapB1\Toolkit\DTOs\Admin\SpecialPriceDto;

/**
 * Special Price actions.
 */
final class SpecialPriceAction extends BaseAction
{
    protected string $entity = 'SpecialPrices';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_array($args[0]) ? $this->create($args[0]) : $this->findByItemAndCard($args[0], $args[1] ?? '');
    }

    /**
     * Find special price by item code and card code.
     */
    public function findByItemAndCard(string $itemCode, string $cardCode): SpecialPriceDto
    {
        $data = $this->client()
            ->service($this->entity)
            ->find("ItemCode='{$itemCode}',CardCode='{$cardCode}'");

        return SpecialPriceDto::fromResponse($data);
    }

    /**
     * @param  SpecialPriceBuilder|array<string, mixed>  $data
     */
    public function create(SpecialPriceBuilder|array $data): SpecialPriceDto
    {
        $payload = $data instanceof SpecialPriceBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return SpecialPriceDto::fromResponse($response);
    }

    /**
     * @param  SpecialPriceBuilder|array<string, mixed>  $data
     */
    public function update(string $itemCode, string $cardCode, SpecialPriceBuilder|array $data): SpecialPriceDto
    {
        $payload = $data instanceof SpecialPriceBuilder ? $data->build() : $data;
        $response = $this->client()
            ->service($this->entity)
            ->update("ItemCode='{$itemCode}',CardCode='{$cardCode}'", $payload);

        return SpecialPriceDto::fromResponse($response);
    }

    public function delete(string $itemCode, string $cardCode): bool
    {
        $this->client()
            ->service($this->entity)
            ->delete("ItemCode='{$itemCode}',CardCode='{$cardCode}'");

        return true;
    }

    /**
     * @return array<SpecialPriceDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => SpecialPriceDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Get special prices by item.
     *
     * @return array<SpecialPriceDto>
     */
    public function getByItem(string $itemCode): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("ItemCode eq '{$itemCode}'")
            ->get();

        return array_map(fn (array $item) => SpecialPriceDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Get special prices by card.
     *
     * @return array<SpecialPriceDto>
     */
    public function getByCard(string $cardCode): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("CardCode eq '{$cardCode}'")
            ->get();

        return array_map(fn (array $item) => SpecialPriceDto::fromResponse($item), $response['value'] ?? []);
    }
}

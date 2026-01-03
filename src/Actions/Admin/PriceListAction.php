<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Admin;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Admin\PriceListBuilder;
use SapB1\Toolkit\DTOs\Admin\PriceListDto;
use SapB1\Toolkit\Enums\BoYesNo;

/**
 * Price List actions.
 */
final class PriceListAction extends BaseAction
{
    protected string $entity = 'PriceLists';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $priceListNo): PriceListDto
    {
        $data = $this->client()->service($this->entity)->find($priceListNo);

        return PriceListDto::fromResponse($data);
    }

    /**
     * @param  PriceListBuilder|array<string, mixed>  $data
     */
    public function create(PriceListBuilder|array $data): PriceListDto
    {
        $payload = $data instanceof PriceListBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return PriceListDto::fromResponse($response);
    }

    /**
     * @param  PriceListBuilder|array<string, mixed>  $data
     */
    public function update(int $priceListNo, PriceListBuilder|array $data): PriceListDto
    {
        $payload = $data instanceof PriceListBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($priceListNo, $payload);

        return PriceListDto::fromResponse($response);
    }

    public function delete(int $priceListNo): bool
    {
        $this->client()->service($this->entity)->delete($priceListNo);

        return true;
    }

    /**
     * @return array<PriceListDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => PriceListDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Get active price lists.
     *
     * @return array<PriceListDto>
     */
    public function getActive(): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("Active eq '".BoYesNo::Yes->value."'")
            ->get();

        return array_map(fn (array $item) => PriceListDto::fromResponse($item), $response['value'] ?? []);
    }
}

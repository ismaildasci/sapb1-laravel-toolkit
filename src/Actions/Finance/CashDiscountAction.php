<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Finance;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Finance\CashDiscountBuilder;
use SapB1\Toolkit\DTOs\Finance\CashDiscountDto;

/**
 * Cash Discount actions.
 */
final class CashDiscountAction extends BaseAction
{
    protected string $entity = 'CashDiscounts';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_string($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(string $code): CashDiscountDto
    {
        $data = $this->client()->service($this->entity)->find($code);

        return CashDiscountDto::fromResponse($data);
    }

    /**
     * @param  CashDiscountBuilder|array<string, mixed>  $data
     */
    public function create(CashDiscountBuilder|array $data): CashDiscountDto
    {
        $payload = $data instanceof CashDiscountBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return CashDiscountDto::fromResponse($response);
    }

    /**
     * @param  CashDiscountBuilder|array<string, mixed>  $data
     */
    public function update(string $code, CashDiscountBuilder|array $data): CashDiscountDto
    {
        $payload = $data instanceof CashDiscountBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($code, $payload);

        return CashDiscountDto::fromResponse($response);
    }

    public function delete(string $code): bool
    {
        $this->client()->service($this->entity)->delete($code);

        return true;
    }

    /**
     * @return array<CashDiscountDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => CashDiscountDto::fromResponse($item), $response['value'] ?? []);
    }
}

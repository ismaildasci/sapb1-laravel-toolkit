<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Finance;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Finance\CashFlowLineItemBuilder;
use SapB1\Toolkit\DTOs\Finance\CashFlowLineItemDto;

/**
 * Cash Flow Line Item actions.
 */
final class CashFlowLineItemAction extends BaseAction
{
    protected string $entity = 'CashFlowLineItems';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $lineItemID): CashFlowLineItemDto
    {
        $data = $this->client()->service($this->entity)->find($lineItemID);

        return CashFlowLineItemDto::fromResponse($data);
    }

    /**
     * @param  CashFlowLineItemBuilder|array<string, mixed>  $data
     */
    public function create(CashFlowLineItemBuilder|array $data): CashFlowLineItemDto
    {
        $payload = $data instanceof CashFlowLineItemBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return CashFlowLineItemDto::fromResponse($response);
    }

    /**
     * @param  CashFlowLineItemBuilder|array<string, mixed>  $data
     */
    public function update(int $lineItemID, CashFlowLineItemBuilder|array $data): CashFlowLineItemDto
    {
        $payload = $data instanceof CashFlowLineItemBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($lineItemID, $payload);

        return CashFlowLineItemDto::fromResponse($response);
    }

    public function delete(int $lineItemID): bool
    {
        $this->client()->service($this->entity)->delete($lineItemID);

        return true;
    }

    /**
     * @return array<CashFlowLineItemDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => CashFlowLineItemDto::fromResponse($item), $response['value'] ?? []);
    }
}

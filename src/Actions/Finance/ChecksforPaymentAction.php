<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Finance;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Finance\ChecksforPaymentBuilder;
use SapB1\Toolkit\DTOs\Finance\ChecksforPaymentDto;

/**
 * Checks for Payment actions.
 */
final class ChecksforPaymentAction extends BaseAction
{
    protected string $entity = 'ChecksforPayment';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $checkKey): ChecksforPaymentDto
    {
        $data = $this->client()->service($this->entity)->find($checkKey);

        return ChecksforPaymentDto::fromResponse($data);
    }

    /**
     * @param  ChecksforPaymentBuilder|array<string, mixed>  $data
     */
    public function create(ChecksforPaymentBuilder|array $data): ChecksforPaymentDto
    {
        $payload = $data instanceof ChecksforPaymentBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return ChecksforPaymentDto::fromResponse($response);
    }

    /**
     * @param  ChecksforPaymentBuilder|array<string, mixed>  $data
     */
    public function update(int $checkKey, ChecksforPaymentBuilder|array $data): ChecksforPaymentDto
    {
        $payload = $data instanceof ChecksforPaymentBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($checkKey, $payload);

        return ChecksforPaymentDto::fromResponse($response);
    }

    public function cancel(int $checkKey): bool
    {
        $this->client()->service($this->entity)->action($checkKey, 'Cancel');

        return true;
    }

    /**
     * @return array<ChecksforPaymentDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => ChecksforPaymentDto::fromResponse($item), $response['value'] ?? []);
    }
}

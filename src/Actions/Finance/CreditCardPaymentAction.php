<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Finance;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Finance\CreditCardPaymentBuilder;
use SapB1\Toolkit\DTOs\Finance\CreditCardPaymentDto;

/**
 * Credit Card Payment actions.
 */
final class CreditCardPaymentAction extends BaseAction
{
    protected string $entity = 'CreditCardPayments';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $dueDateCode): CreditCardPaymentDto
    {
        $data = $this->client()->service($this->entity)->find($dueDateCode);

        return CreditCardPaymentDto::fromResponse($data);
    }

    /**
     * @param  CreditCardPaymentBuilder|array<string, mixed>  $data
     */
    public function create(CreditCardPaymentBuilder|array $data): CreditCardPaymentDto
    {
        $payload = $data instanceof CreditCardPaymentBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return CreditCardPaymentDto::fromResponse($response);
    }

    /**
     * @param  CreditCardPaymentBuilder|array<string, mixed>  $data
     */
    public function update(int $dueDateCode, CreditCardPaymentBuilder|array $data): CreditCardPaymentDto
    {
        $payload = $data instanceof CreditCardPaymentBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($dueDateCode, $payload);

        return CreditCardPaymentDto::fromResponse($response);
    }

    public function delete(int $dueDateCode): bool
    {
        $this->client()->service($this->entity)->delete($dueDateCode);

        return true;
    }

    /**
     * @return array<CreditCardPaymentDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => CreditCardPaymentDto::fromResponse($item), $response['value'] ?? []);
    }
}

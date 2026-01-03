<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Finance;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Finance\PaymentDraftBuilder;
use SapB1\Toolkit\DTOs\Finance\PaymentDraftDto;

/**
 * Payment Draft actions.
 */
final class PaymentDraftAction extends BaseAction
{
    protected string $entity = 'PaymentDrafts';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $docEntry): PaymentDraftDto
    {
        $data = $this->client()->service($this->entity)->find($docEntry);

        return PaymentDraftDto::fromResponse($data);
    }

    /**
     * @param  PaymentDraftBuilder|array<string, mixed>  $data
     */
    public function create(PaymentDraftBuilder|array $data): PaymentDraftDto
    {
        $payload = $data instanceof PaymentDraftBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return PaymentDraftDto::fromResponse($response);
    }

    /**
     * @param  PaymentDraftBuilder|array<string, mixed>  $data
     */
    public function update(int $docEntry, PaymentDraftBuilder|array $data): PaymentDraftDto
    {
        $payload = $data instanceof PaymentDraftBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($docEntry, $payload);

        return PaymentDraftDto::fromResponse($response);
    }

    public function cancel(int $docEntry): bool
    {
        $this->client()->service($this->entity)->action($docEntry, 'Cancel');

        return true;
    }

    /**
     * @return array<PaymentDraftDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => PaymentDraftDto::fromResponse($item), $response['value'] ?? []);
    }
}

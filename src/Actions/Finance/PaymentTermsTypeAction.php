<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Finance;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Finance\PaymentTermsTypeBuilder;
use SapB1\Toolkit\DTOs\Finance\PaymentTermsTypeDto;

/**
 * Payment Terms Type actions.
 */
final class PaymentTermsTypeAction extends BaseAction
{
    protected string $entity = 'PaymentTermsTypes';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $groupNumber): PaymentTermsTypeDto
    {
        $data = $this->client()->service($this->entity)->find($groupNumber);

        return PaymentTermsTypeDto::fromResponse($data);
    }

    /**
     * @param  PaymentTermsTypeBuilder|array<string, mixed>  $data
     */
    public function create(PaymentTermsTypeBuilder|array $data): PaymentTermsTypeDto
    {
        $payload = $data instanceof PaymentTermsTypeBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return PaymentTermsTypeDto::fromResponse($response);
    }

    /**
     * @param  PaymentTermsTypeBuilder|array<string, mixed>  $data
     */
    public function update(int $groupNumber, PaymentTermsTypeBuilder|array $data): PaymentTermsTypeDto
    {
        $payload = $data instanceof PaymentTermsTypeBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($groupNumber, $payload);

        return PaymentTermsTypeDto::fromResponse($response);
    }

    public function delete(int $groupNumber): bool
    {
        $this->client()->service($this->entity)->delete($groupNumber);

        return true;
    }

    /**
     * @return array<PaymentTermsTypeDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => PaymentTermsTypeDto::fromResponse($item), $response['value'] ?? []);
    }
}

<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Finance;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Finance\BankBuilder;
use SapB1\Toolkit\DTOs\Finance\BankDto;

/**
 * Bank actions.
 */
final class BankAction extends BaseAction
{
    protected string $entity = 'Banks';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_string($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(string $bankCode): BankDto
    {
        $data = $this->client()->service($this->entity)->find($bankCode);

        return BankDto::fromResponse($data);
    }

    /**
     * @param  BankBuilder|array<string, mixed>  $data
     */
    public function create(BankBuilder|array $data): BankDto
    {
        $payload = $data instanceof BankBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return BankDto::fromResponse($response);
    }

    /**
     * @param  BankBuilder|array<string, mixed>  $data
     */
    public function update(string $bankCode, BankBuilder|array $data): BankDto
    {
        $payload = $data instanceof BankBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($bankCode, $payload);

        return BankDto::fromResponse($response);
    }

    public function delete(string $bankCode): bool
    {
        $this->client()->service($this->entity)->delete($bankCode);

        return true;
    }

    /**
     * @return array<BankDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => BankDto::fromResponse($item), $response['value'] ?? []);
    }
}

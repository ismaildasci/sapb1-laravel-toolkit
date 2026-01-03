<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Finance;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Finance\DepositBuilder;
use SapB1\Toolkit\DTOs\Finance\DepositDto;

/**
 * Deposit actions.
 */
final class DepositAction extends BaseAction
{
    protected string $entity = 'Deposits';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $absEntry): DepositDto
    {
        $data = $this->client()->service($this->entity)->find($absEntry);

        return DepositDto::fromResponse($data);
    }

    /**
     * @param  DepositBuilder|array<string, mixed>  $data
     */
    public function create(DepositBuilder|array $data): DepositDto
    {
        $payload = $data instanceof DepositBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return DepositDto::fromResponse($response);
    }

    /**
     * @param  DepositBuilder|array<string, mixed>  $data
     */
    public function update(int $absEntry, DepositBuilder|array $data): DepositDto
    {
        $payload = $data instanceof DepositBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($absEntry, $payload);

        return DepositDto::fromResponse($response);
    }

    public function cancel(int $absEntry): bool
    {
        $this->client()->service($this->entity)->action($absEntry, 'Cancel');

        return true;
    }

    /**
     * @return array<DepositDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => DepositDto::fromResponse($item), $response['value'] ?? []);
    }
}

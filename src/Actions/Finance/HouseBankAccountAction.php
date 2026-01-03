<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Finance;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Finance\HouseBankAccountBuilder;
use SapB1\Toolkit\DTOs\Finance\HouseBankAccountDto;

/**
 * House Bank Account actions.
 */
final class HouseBankAccountAction extends BaseAction
{
    protected string $entity = 'HouseBankAccounts';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $absoluteEntry): HouseBankAccountDto
    {
        $data = $this->client()->service($this->entity)->find($absoluteEntry);

        return HouseBankAccountDto::fromResponse($data);
    }

    /**
     * @param  HouseBankAccountBuilder|array<string, mixed>  $data
     */
    public function create(HouseBankAccountBuilder|array $data): HouseBankAccountDto
    {
        $payload = $data instanceof HouseBankAccountBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return HouseBankAccountDto::fromResponse($response);
    }

    /**
     * @param  HouseBankAccountBuilder|array<string, mixed>  $data
     */
    public function update(int $absoluteEntry, HouseBankAccountBuilder|array $data): HouseBankAccountDto
    {
        $payload = $data instanceof HouseBankAccountBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($absoluteEntry, $payload);

        return HouseBankAccountDto::fromResponse($response);
    }

    public function delete(int $absoluteEntry): bool
    {
        $this->client()->service($this->entity)->delete($absoluteEntry);

        return true;
    }

    /**
     * @return array<HouseBankAccountDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => HouseBankAccountDto::fromResponse($item), $response['value'] ?? []);
    }
}

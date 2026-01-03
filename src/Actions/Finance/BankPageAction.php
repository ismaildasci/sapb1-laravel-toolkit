<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Finance;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Finance\BankPageBuilder;
use SapB1\Toolkit\DTOs\Finance\BankPageDto;

/**
 * Bank Page actions.
 */
final class BankPageAction extends BaseAction
{
    protected string $entity = 'BankPages';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_array($args[0]) && ! is_int(array_key_first($args[0])) ? $this->create($args[0]) : $this->find($args[0], $args[1] ?? 0);
    }

    public function find(int $accountCode, int $sequence): BankPageDto
    {
        // BankPages uses composite key (AccountCode, Sequence)
        $key = "AccountCode={$accountCode},Sequence={$sequence}";
        $data = $this->client()->service($this->entity)->find($key);

        return BankPageDto::fromResponse($data);
    }

    /**
     * @param  BankPageBuilder|array<string, mixed>  $data
     */
    public function create(BankPageBuilder|array $data): BankPageDto
    {
        $payload = $data instanceof BankPageBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return BankPageDto::fromResponse($response);
    }

    /**
     * @param  BankPageBuilder|array<string, mixed>  $data
     */
    public function update(int $accountCode, int $sequence, BankPageBuilder|array $data): BankPageDto
    {
        $key = "AccountCode={$accountCode},Sequence={$sequence}";
        $payload = $data instanceof BankPageBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($key, $payload);

        return BankPageDto::fromResponse($response);
    }

    public function delete(int $accountCode, int $sequence): bool
    {
        $key = "AccountCode={$accountCode},Sequence={$sequence}";
        $this->client()->service($this->entity)->delete($key);

        return true;
    }

    /**
     * @return array<BankPageDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => BankPageDto::fromResponse($item), $response['value'] ?? []);
    }
}

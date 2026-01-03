<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Finance;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Finance\CurrencyBuilder;
use SapB1\Toolkit\DTOs\Finance\CurrencyDto;

/**
 * Currency actions.
 */
final class CurrencyAction extends BaseAction
{
    protected string $entity = 'Currencies';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_string($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(string $code): CurrencyDto
    {
        $data = $this->client()->service($this->entity)->find($code);

        return CurrencyDto::fromResponse($data);
    }

    /**
     * @param  CurrencyBuilder|array<string, mixed>  $data
     */
    public function create(CurrencyBuilder|array $data): CurrencyDto
    {
        $payload = $data instanceof CurrencyBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return CurrencyDto::fromResponse($response);
    }

    /**
     * @param  CurrencyBuilder|array<string, mixed>  $data
     */
    public function update(string $code, CurrencyBuilder|array $data): CurrencyDto
    {
        $payload = $data instanceof CurrencyBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($code, $payload);

        return CurrencyDto::fromResponse($response);
    }

    public function delete(string $code): bool
    {
        $this->client()->service($this->entity)->delete($code);

        return true;
    }

    /**
     * @return array<CurrencyDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => CurrencyDto::fromResponse($item), $response['value'] ?? []);
    }
}

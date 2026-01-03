<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Finance;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Finance\BankStatementBuilder;
use SapB1\Toolkit\DTOs\Finance\BankStatementDto;

/**
 * Bank Statement actions.
 */
final class BankStatementAction extends BaseAction
{
    protected string $entity = 'BankStatements';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $internalNumber): BankStatementDto
    {
        $data = $this->client()->service($this->entity)->find($internalNumber);

        return BankStatementDto::fromResponse($data);
    }

    /**
     * @param  BankStatementBuilder|array<string, mixed>  $data
     */
    public function create(BankStatementBuilder|array $data): BankStatementDto
    {
        $payload = $data instanceof BankStatementBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return BankStatementDto::fromResponse($response);
    }

    /**
     * @param  BankStatementBuilder|array<string, mixed>  $data
     */
    public function update(int $internalNumber, BankStatementBuilder|array $data): BankStatementDto
    {
        $payload = $data instanceof BankStatementBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($internalNumber, $payload);

        return BankStatementDto::fromResponse($response);
    }

    public function delete(int $internalNumber): bool
    {
        $this->client()->service($this->entity)->delete($internalNumber);

        return true;
    }

    /**
     * @return array<BankStatementDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => BankStatementDto::fromResponse($item), $response['value'] ?? []);
    }
}

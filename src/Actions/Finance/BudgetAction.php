<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Finance;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Finance\BudgetBuilder;
use SapB1\Toolkit\DTOs\Finance\BudgetDto;

/**
 * Budget actions.
 */
final class BudgetAction extends BaseAction
{
    protected string $entity = 'Budgets';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $numerator): BudgetDto
    {
        $data = $this->client()->service($this->entity)->find($numerator);

        return BudgetDto::fromResponse($data);
    }

    /**
     * @param  BudgetBuilder|array<string, mixed>  $data
     */
    public function create(BudgetBuilder|array $data): BudgetDto
    {
        $payload = $data instanceof BudgetBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return BudgetDto::fromResponse($response);
    }

    /**
     * @param  BudgetBuilder|array<string, mixed>  $data
     */
    public function update(int $numerator, BudgetBuilder|array $data): BudgetDto
    {
        $payload = $data instanceof BudgetBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($numerator, $payload);

        return BudgetDto::fromResponse($response);
    }

    public function delete(int $numerator): bool
    {
        $this->client()->service($this->entity)->delete($numerator);

        return true;
    }

    /**
     * @return array<BudgetDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => BudgetDto::fromResponse($item), $response['value'] ?? []);
    }
}

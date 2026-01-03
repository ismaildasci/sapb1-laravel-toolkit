<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Finance;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Finance\BudgetScenarioBuilder;
use SapB1\Toolkit\DTOs\Finance\BudgetScenarioDto;

/**
 * Budget Scenario actions.
 */
final class BudgetScenarioAction extends BaseAction
{
    protected string $entity = 'BudgetScenarios';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $numerator): BudgetScenarioDto
    {
        $data = $this->client()->service($this->entity)->find($numerator);

        return BudgetScenarioDto::fromResponse($data);
    }

    /**
     * @param  BudgetScenarioBuilder|array<string, mixed>  $data
     */
    public function create(BudgetScenarioBuilder|array $data): BudgetScenarioDto
    {
        $payload = $data instanceof BudgetScenarioBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return BudgetScenarioDto::fromResponse($response);
    }

    /**
     * @param  BudgetScenarioBuilder|array<string, mixed>  $data
     */
    public function update(int $numerator, BudgetScenarioBuilder|array $data): BudgetScenarioDto
    {
        $payload = $data instanceof BudgetScenarioBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($numerator, $payload);

        return BudgetScenarioDto::fromResponse($response);
    }

    public function delete(int $numerator): bool
    {
        $this->client()->service($this->entity)->delete($numerator);

        return true;
    }

    /**
     * @return array<BudgetScenarioDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => BudgetScenarioDto::fromResponse($item), $response['value'] ?? []);
    }
}

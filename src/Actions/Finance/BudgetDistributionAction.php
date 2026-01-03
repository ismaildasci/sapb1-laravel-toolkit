<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Finance;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Finance\BudgetDistributionBuilder;
use SapB1\Toolkit\DTOs\Finance\BudgetDistributionDto;

/**
 * Budget Distribution actions.
 */
final class BudgetDistributionAction extends BaseAction
{
    protected string $entity = 'BudgetDistributions';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $divisionCode): BudgetDistributionDto
    {
        $data = $this->client()->service($this->entity)->find($divisionCode);

        return BudgetDistributionDto::fromResponse($data);
    }

    /**
     * @param  BudgetDistributionBuilder|array<string, mixed>  $data
     */
    public function create(BudgetDistributionBuilder|array $data): BudgetDistributionDto
    {
        $payload = $data instanceof BudgetDistributionBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return BudgetDistributionDto::fromResponse($response);
    }

    /**
     * @param  BudgetDistributionBuilder|array<string, mixed>  $data
     */
    public function update(int $divisionCode, BudgetDistributionBuilder|array $data): BudgetDistributionDto
    {
        $payload = $data instanceof BudgetDistributionBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($divisionCode, $payload);

        return BudgetDistributionDto::fromResponse($response);
    }

    public function delete(int $divisionCode): bool
    {
        $this->client()->service($this->entity)->delete($divisionCode);

        return true;
    }

    /**
     * @return array<BudgetDistributionDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => BudgetDistributionDto::fromResponse($item), $response['value'] ?? []);
    }
}

<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Finance;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Finance\InternalReconciliationBuilder;
use SapB1\Toolkit\DTOs\Finance\InternalReconciliationDto;

/**
 * Internal Reconciliation actions.
 */
final class InternalReconciliationAction extends BaseAction
{
    protected string $entity = 'InternalReconciliations';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $reconNum): InternalReconciliationDto
    {
        $data = $this->client()->service($this->entity)->find($reconNum);

        return InternalReconciliationDto::fromResponse($data);
    }

    /**
     * @param  InternalReconciliationBuilder|array<string, mixed>  $data
     */
    public function create(InternalReconciliationBuilder|array $data): InternalReconciliationDto
    {
        $payload = $data instanceof InternalReconciliationBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return InternalReconciliationDto::fromResponse($response);
    }

    public function cancel(int $reconNum): bool
    {
        $this->client()->service($this->entity)->action($reconNum, 'Cancel');

        return true;
    }

    /**
     * @return array<InternalReconciliationDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => InternalReconciliationDto::fromResponse($item), $response['value'] ?? []);
    }
}

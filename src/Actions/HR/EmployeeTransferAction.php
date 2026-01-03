<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\HR;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\HR\EmployeeTransferBuilder;
use SapB1\Toolkit\DTOs\HR\EmployeeTransferDto;

/**
 * Employee Transfer actions.
 */
final class EmployeeTransferAction extends BaseAction
{
    protected string $entity = 'EmployeeTransfers';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $transferId): EmployeeTransferDto
    {
        $data = $this->client()->service($this->entity)->find($transferId);

        return EmployeeTransferDto::fromResponse($data);
    }

    /**
     * @param  EmployeeTransferBuilder|array<string, mixed>  $data
     */
    public function create(EmployeeTransferBuilder|array $data): EmployeeTransferDto
    {
        $payload = $data instanceof EmployeeTransferBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return EmployeeTransferDto::fromResponse($response);
    }

    /**
     * @param  EmployeeTransferBuilder|array<string, mixed>  $data
     */
    public function update(int $transferId, EmployeeTransferBuilder|array $data): EmployeeTransferDto
    {
        $payload = $data instanceof EmployeeTransferBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($transferId, $payload);

        return EmployeeTransferDto::fromResponse($response);
    }

    /**
     * @return array<EmployeeTransferDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => EmployeeTransferDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Get transfers by employee.
     *
     * @return array<EmployeeTransferDto>
     */
    public function getByEmployee(int $employeeId): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("EmployeeID eq {$employeeId}")
            ->get();

        return array_map(fn (array $item) => EmployeeTransferDto::fromResponse($item), $response['value'] ?? []);
    }
}

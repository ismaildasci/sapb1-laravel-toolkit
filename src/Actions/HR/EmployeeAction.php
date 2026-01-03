<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\HR;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\HR\EmployeeBuilder;
use SapB1\Toolkit\DTOs\HR\EmployeeDto;
use SapB1\Toolkit\Enums\EmployeeStatusType;

/**
 * Employee actions.
 */
final class EmployeeAction extends BaseAction
{
    protected string $entity = 'EmployeesInfo';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $employeeID): EmployeeDto
    {
        $data = $this->client()->service($this->entity)->find($employeeID);

        return EmployeeDto::fromResponse($data);
    }

    /**
     * @param  EmployeeBuilder|array<string, mixed>  $data
     */
    public function create(EmployeeBuilder|array $data): EmployeeDto
    {
        $payload = $data instanceof EmployeeBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return EmployeeDto::fromResponse($response);
    }

    /**
     * @param  EmployeeBuilder|array<string, mixed>  $data
     */
    public function update(int $employeeID, EmployeeBuilder|array $data): EmployeeDto
    {
        $payload = $data instanceof EmployeeBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($employeeID, $payload);

        return EmployeeDto::fromResponse($response);
    }

    public function delete(int $employeeID): bool
    {
        $this->client()->service($this->entity)->delete($employeeID);

        return true;
    }

    /**
     * @return array<EmployeeDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => EmployeeDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Get active employees.
     *
     * @return array<EmployeeDto>
     */
    public function getActive(): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("StatusCode eq '".EmployeeStatusType::Active->value."'")
            ->get();

        return array_map(fn (array $item) => EmployeeDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Get employees by department.
     *
     * @return array<EmployeeDto>
     */
    public function getByDepartment(int $departmentId): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("Department eq {$departmentId}")
            ->get();

        return array_map(fn (array $item) => EmployeeDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Get employees by branch.
     *
     * @return array<EmployeeDto>
     */
    public function getByBranch(int $branchId): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("Branch eq {$branchId}")
            ->get();

        return array_map(fn (array $item) => EmployeeDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Get employees by manager.
     *
     * @return array<EmployeeDto>
     */
    public function getByManager(int $managerId): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("Manager eq {$managerId}")
            ->get();

        return array_map(fn (array $item) => EmployeeDto::fromResponse($item), $response['value'] ?? []);
    }
}

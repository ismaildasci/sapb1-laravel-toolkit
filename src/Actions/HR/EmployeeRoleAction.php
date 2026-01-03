<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\HR;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\HR\EmployeeRoleBuilder;
use SapB1\Toolkit\DTOs\HR\EmployeeRoleDto;

/**
 * Employee Role Setup actions.
 */
final class EmployeeRoleAction extends BaseAction
{
    protected string $entity = 'EmployeeRolesSetup';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $typeId): EmployeeRoleDto
    {
        $data = $this->client()->service($this->entity)->find($typeId);

        return EmployeeRoleDto::fromResponse($data);
    }

    /**
     * @param  EmployeeRoleBuilder|array<string, mixed>  $data
     */
    public function create(EmployeeRoleBuilder|array $data): EmployeeRoleDto
    {
        $payload = $data instanceof EmployeeRoleBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return EmployeeRoleDto::fromResponse($response);
    }

    /**
     * @param  EmployeeRoleBuilder|array<string, mixed>  $data
     */
    public function update(int $typeId, EmployeeRoleBuilder|array $data): EmployeeRoleDto
    {
        $payload = $data instanceof EmployeeRoleBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($typeId, $payload);

        return EmployeeRoleDto::fromResponse($response);
    }

    public function delete(int $typeId): bool
    {
        $this->client()->service($this->entity)->delete($typeId);

        return true;
    }

    /**
     * @return array<EmployeeRoleDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => EmployeeRoleDto::fromResponse($item), $response['value'] ?? []);
    }
}

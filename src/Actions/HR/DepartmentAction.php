<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\HR;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\HR\DepartmentBuilder;
use SapB1\Toolkit\DTOs\HR\DepartmentDto;

/**
 * Department actions.
 */
final class DepartmentAction extends BaseAction
{
    protected string $entity = 'Departments';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $code): DepartmentDto
    {
        $data = $this->client()->service($this->entity)->find($code);

        return DepartmentDto::fromResponse($data);
    }

    /**
     * @param  DepartmentBuilder|array<string, mixed>  $data
     */
    public function create(DepartmentBuilder|array $data): DepartmentDto
    {
        $payload = $data instanceof DepartmentBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return DepartmentDto::fromResponse($response);
    }

    /**
     * @param  DepartmentBuilder|array<string, mixed>  $data
     */
    public function update(int $code, DepartmentBuilder|array $data): DepartmentDto
    {
        $payload = $data instanceof DepartmentBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($code, $payload);

        return DepartmentDto::fromResponse($response);
    }

    public function delete(int $code): bool
    {
        $this->client()->service($this->entity)->delete($code);

        return true;
    }

    /**
     * @return array<DepartmentDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => DepartmentDto::fromResponse($item), $response['value'] ?? []);
    }
}

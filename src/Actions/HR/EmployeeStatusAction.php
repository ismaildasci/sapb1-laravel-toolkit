<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\HR;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\HR\EmployeeStatusBuilder;
use SapB1\Toolkit\DTOs\HR\EmployeeStatusDto;

/**
 * Employee Status actions.
 */
final class EmployeeStatusAction extends BaseAction
{
    protected string $entity = 'EmployeeStatus';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $statusId): EmployeeStatusDto
    {
        $data = $this->client()->service($this->entity)->find($statusId);

        return EmployeeStatusDto::fromResponse($data);
    }

    /**
     * @param  EmployeeStatusBuilder|array<string, mixed>  $data
     */
    public function create(EmployeeStatusBuilder|array $data): EmployeeStatusDto
    {
        $payload = $data instanceof EmployeeStatusBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return EmployeeStatusDto::fromResponse($response);
    }

    /**
     * @param  EmployeeStatusBuilder|array<string, mixed>  $data
     */
    public function update(int $statusId, EmployeeStatusBuilder|array $data): EmployeeStatusDto
    {
        $payload = $data instanceof EmployeeStatusBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($statusId, $payload);

        return EmployeeStatusDto::fromResponse($response);
    }

    public function delete(int $statusId): bool
    {
        $this->client()->service($this->entity)->delete($statusId);

        return true;
    }

    /**
     * @return array<EmployeeStatusDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => EmployeeStatusDto::fromResponse($item), $response['value'] ?? []);
    }
}

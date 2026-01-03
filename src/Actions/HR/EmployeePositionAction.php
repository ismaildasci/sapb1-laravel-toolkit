<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\HR;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\HR\EmployeePositionBuilder;
use SapB1\Toolkit\DTOs\HR\EmployeePositionDto;

/**
 * Employee Position actions.
 */
final class EmployeePositionAction extends BaseAction
{
    protected string $entity = 'EmployeePosition';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $positionId): EmployeePositionDto
    {
        $data = $this->client()->service($this->entity)->find($positionId);

        return EmployeePositionDto::fromResponse($data);
    }

    /**
     * @param  EmployeePositionBuilder|array<string, mixed>  $data
     */
    public function create(EmployeePositionBuilder|array $data): EmployeePositionDto
    {
        $payload = $data instanceof EmployeePositionBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return EmployeePositionDto::fromResponse($response);
    }

    /**
     * @param  EmployeePositionBuilder|array<string, mixed>  $data
     */
    public function update(int $positionId, EmployeePositionBuilder|array $data): EmployeePositionDto
    {
        $payload = $data instanceof EmployeePositionBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($positionId, $payload);

        return EmployeePositionDto::fromResponse($response);
    }

    public function delete(int $positionId): bool
    {
        $this->client()->service($this->entity)->delete($positionId);

        return true;
    }

    /**
     * @return array<EmployeePositionDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => EmployeePositionDto::fromResponse($item), $response['value'] ?? []);
    }
}

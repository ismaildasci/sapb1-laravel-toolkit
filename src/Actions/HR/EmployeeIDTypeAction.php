<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\HR;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\HR\EmployeeIDTypeBuilder;
use SapB1\Toolkit\DTOs\HR\EmployeeIDTypeDto;

/**
 * Employee ID Type actions.
 */
final class EmployeeIDTypeAction extends BaseAction
{
    protected string $entity = 'EmployeeIDType';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_string($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(string $idType): EmployeeIDTypeDto
    {
        $data = $this->client()->service($this->entity)->find($idType);

        return EmployeeIDTypeDto::fromResponse($data);
    }

    /**
     * @param  EmployeeIDTypeBuilder|array<string, mixed>  $data
     */
    public function create(EmployeeIDTypeBuilder|array $data): EmployeeIDTypeDto
    {
        $payload = $data instanceof EmployeeIDTypeBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return EmployeeIDTypeDto::fromResponse($response);
    }

    /**
     * @param  EmployeeIDTypeBuilder|array<string, mixed>  $data
     */
    public function update(string $idType, EmployeeIDTypeBuilder|array $data): EmployeeIDTypeDto
    {
        $payload = $data instanceof EmployeeIDTypeBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($idType, $payload);

        return EmployeeIDTypeDto::fromResponse($response);
    }

    public function delete(string $idType): bool
    {
        $this->client()->service($this->entity)->delete($idType);

        return true;
    }

    /**
     * @return array<EmployeeIDTypeDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => EmployeeIDTypeDto::fromResponse($item), $response['value'] ?? []);
    }
}

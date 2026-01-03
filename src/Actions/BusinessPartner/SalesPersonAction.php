<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\BusinessPartner;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\BusinessPartner\SalesPersonBuilder;
use SapB1\Toolkit\DTOs\BusinessPartner\SalesPersonDto;

/**
 * Sales Person actions.
 */
final class SalesPersonAction extends BaseAction
{
    protected string $entity = 'SalesPersons';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $salesEmployeeCode): SalesPersonDto
    {
        $data = $this->client()->service($this->entity)->find($salesEmployeeCode);

        return SalesPersonDto::fromResponse($data);
    }

    /**
     * @param  SalesPersonBuilder|array<string, mixed>  $data
     */
    public function create(SalesPersonBuilder|array $data): SalesPersonDto
    {
        $payload = $data instanceof SalesPersonBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return SalesPersonDto::fromResponse($response);
    }

    /**
     * @param  SalesPersonBuilder|array<string, mixed>  $data
     */
    public function update(int $salesEmployeeCode, SalesPersonBuilder|array $data): SalesPersonDto
    {
        $payload = $data instanceof SalesPersonBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($salesEmployeeCode, $payload);

        return SalesPersonDto::fromResponse($response);
    }

    public function delete(int $salesEmployeeCode): bool
    {
        $this->client()->service($this->entity)->delete($salesEmployeeCode);

        return true;
    }

    /**
     * @return array<SalesPersonDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => SalesPersonDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Get active sales persons.
     *
     * @return array<SalesPersonDto>
     */
    public function getActive(): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("Active eq 'tYES'")
            ->get();

        return array_map(fn (array $item) => SalesPersonDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Get sales persons by commission group.
     *
     * @return array<SalesPersonDto>
     */
    public function getByCommissionGroup(int $commissionGroup): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("CommissionGroup eq {$commissionGroup}")
            ->get();

        return array_map(fn (array $item) => SalesPersonDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Search sales persons by name.
     *
     * @return array<SalesPersonDto>
     */
    public function search(string $query): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("contains(SalesEmployeeName, '{$query}')")
            ->get();

        return array_map(fn (array $item) => SalesPersonDto::fromResponse($item), $response['value'] ?? []);
    }
}

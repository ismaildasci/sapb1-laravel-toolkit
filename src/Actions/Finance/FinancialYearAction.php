<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Finance;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Finance\FinancialYearBuilder;
use SapB1\Toolkit\DTOs\Finance\FinancialYearDto;

/**
 * Financial Year actions.
 */
final class FinancialYearAction extends BaseAction
{
    protected string $entity = 'FinancialYears';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $absEntry): FinancialYearDto
    {
        $data = $this->client()->service($this->entity)->find($absEntry);

        return FinancialYearDto::fromResponse($data);
    }

    /**
     * @param  FinancialYearBuilder|array<string, mixed>  $data
     */
    public function create(FinancialYearBuilder|array $data): FinancialYearDto
    {
        $payload = $data instanceof FinancialYearBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return FinancialYearDto::fromResponse($response);
    }

    /**
     * @param  FinancialYearBuilder|array<string, mixed>  $data
     */
    public function update(int $absEntry, FinancialYearBuilder|array $data): FinancialYearDto
    {
        $payload = $data instanceof FinancialYearBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($absEntry, $payload);

        return FinancialYearDto::fromResponse($response);
    }

    public function delete(int $absEntry): bool
    {
        $this->client()->service($this->entity)->delete($absEntry);

        return true;
    }

    /**
     * @return array<FinancialYearDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => FinancialYearDto::fromResponse($item), $response['value'] ?? []);
    }
}

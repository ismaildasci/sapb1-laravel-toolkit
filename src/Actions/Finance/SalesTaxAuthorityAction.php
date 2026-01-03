<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Finance;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Finance\SalesTaxAuthorityBuilder;
use SapB1\Toolkit\DTOs\Finance\SalesTaxAuthorityDto;

/**
 * Sales Tax Authority actions.
 */
final class SalesTaxAuthorityAction extends BaseAction
{
    protected string $entity = 'SalesTaxAuthorities';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $code): SalesTaxAuthorityDto
    {
        $data = $this->client()->service($this->entity)->find($code);

        return SalesTaxAuthorityDto::fromResponse($data);
    }

    /**
     * @param  SalesTaxAuthorityBuilder|array<string, mixed>  $data
     */
    public function create(SalesTaxAuthorityBuilder|array $data): SalesTaxAuthorityDto
    {
        $payload = $data instanceof SalesTaxAuthorityBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return SalesTaxAuthorityDto::fromResponse($response);
    }

    /**
     * @param  SalesTaxAuthorityBuilder|array<string, mixed>  $data
     */
    public function update(int $code, SalesTaxAuthorityBuilder|array $data): SalesTaxAuthorityDto
    {
        $payload = $data instanceof SalesTaxAuthorityBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($code, $payload);

        return SalesTaxAuthorityDto::fromResponse($response);
    }

    public function delete(int $code): bool
    {
        $this->client()->service($this->entity)->delete($code);

        return true;
    }

    /**
     * @return array<SalesTaxAuthorityDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => SalesTaxAuthorityDto::fromResponse($item), $response['value'] ?? []);
    }
}

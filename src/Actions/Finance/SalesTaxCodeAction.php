<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Finance;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Finance\SalesTaxCodeBuilder;
use SapB1\Toolkit\DTOs\Finance\SalesTaxCodeDto;

/**
 * Sales Tax Code actions.
 */
final class SalesTaxCodeAction extends BaseAction
{
    protected string $entity = 'SalesTaxCodes';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_string($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(string $code): SalesTaxCodeDto
    {
        $data = $this->client()->service($this->entity)->find($code);

        return SalesTaxCodeDto::fromResponse($data);
    }

    /**
     * @param  SalesTaxCodeBuilder|array<string, mixed>  $data
     */
    public function create(SalesTaxCodeBuilder|array $data): SalesTaxCodeDto
    {
        $payload = $data instanceof SalesTaxCodeBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return SalesTaxCodeDto::fromResponse($response);
    }

    /**
     * @param  SalesTaxCodeBuilder|array<string, mixed>  $data
     */
    public function update(string $code, SalesTaxCodeBuilder|array $data): SalesTaxCodeDto
    {
        $payload = $data instanceof SalesTaxCodeBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($code, $payload);

        return SalesTaxCodeDto::fromResponse($response);
    }

    public function delete(string $code): bool
    {
        $this->client()->service($this->entity)->delete($code);

        return true;
    }

    /**
     * @return array<SalesTaxCodeDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => SalesTaxCodeDto::fromResponse($item), $response['value'] ?? []);
    }
}

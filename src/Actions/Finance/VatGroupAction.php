<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Finance;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Finance\VatGroupBuilder;
use SapB1\Toolkit\DTOs\Finance\VatGroupDto;

/**
 * VAT Group actions.
 */
final class VatGroupAction extends BaseAction
{
    protected string $entity = 'VatGroups';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_string($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(string $code): VatGroupDto
    {
        $data = $this->client()->service($this->entity)->find($code);

        return VatGroupDto::fromResponse($data);
    }

    /**
     * @param  VatGroupBuilder|array<string, mixed>  $data
     */
    public function create(VatGroupBuilder|array $data): VatGroupDto
    {
        $payload = $data instanceof VatGroupBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return VatGroupDto::fromResponse($response);
    }

    /**
     * @param  VatGroupBuilder|array<string, mixed>  $data
     */
    public function update(string $code, VatGroupBuilder|array $data): VatGroupDto
    {
        $payload = $data instanceof VatGroupBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($code, $payload);

        return VatGroupDto::fromResponse($response);
    }

    public function delete(string $code): bool
    {
        $this->client()->service($this->entity)->delete($code);

        return true;
    }

    /**
     * @return array<VatGroupDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => VatGroupDto::fromResponse($item), $response['value'] ?? []);
    }
}

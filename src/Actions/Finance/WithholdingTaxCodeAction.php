<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Finance;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Finance\WithholdingTaxCodeBuilder;
use SapB1\Toolkit\DTOs\Finance\WithholdingTaxCodeDto;

/**
 * Withholding Tax Code actions.
 */
final class WithholdingTaxCodeAction extends BaseAction
{
    protected string $entity = 'WithholdingTaxCodes';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_string($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(string $wTCode): WithholdingTaxCodeDto
    {
        $data = $this->client()->service($this->entity)->find($wTCode);

        return WithholdingTaxCodeDto::fromResponse($data);
    }

    /**
     * @param  WithholdingTaxCodeBuilder|array<string, mixed>  $data
     */
    public function create(WithholdingTaxCodeBuilder|array $data): WithholdingTaxCodeDto
    {
        $payload = $data instanceof WithholdingTaxCodeBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return WithholdingTaxCodeDto::fromResponse($response);
    }

    /**
     * @param  WithholdingTaxCodeBuilder|array<string, mixed>  $data
     */
    public function update(string $wTCode, WithholdingTaxCodeBuilder|array $data): WithholdingTaxCodeDto
    {
        $payload = $data instanceof WithholdingTaxCodeBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($wTCode, $payload);

        return WithholdingTaxCodeDto::fromResponse($response);
    }

    public function delete(string $wTCode): bool
    {
        $this->client()->service($this->entity)->delete($wTCode);

        return true;
    }

    /**
     * @return array<WithholdingTaxCodeDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => WithholdingTaxCodeDto::fromResponse($item), $response['value'] ?? []);
    }
}

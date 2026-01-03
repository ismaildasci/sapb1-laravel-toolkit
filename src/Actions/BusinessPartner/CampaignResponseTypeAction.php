<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\BusinessPartner;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\BusinessPartner\CampaignResponseTypeBuilder;
use SapB1\Toolkit\DTOs\BusinessPartner\CampaignResponseTypeDto;

/**
 * Campaign Response Type actions.
 */
final class CampaignResponseTypeAction extends BaseAction
{
    protected string $entity = 'CampaignResponseType';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_string($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(string $responseType): CampaignResponseTypeDto
    {
        $data = $this->client()->service($this->entity)->find($responseType);

        return CampaignResponseTypeDto::fromResponse($data);
    }

    /**
     * @param  CampaignResponseTypeBuilder|array<string, mixed>  $data
     */
    public function create(CampaignResponseTypeBuilder|array $data): CampaignResponseTypeDto
    {
        $payload = $data instanceof CampaignResponseTypeBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return CampaignResponseTypeDto::fromResponse($response);
    }

    /**
     * @param  CampaignResponseTypeBuilder|array<string, mixed>  $data
     */
    public function update(string $responseType, CampaignResponseTypeBuilder|array $data): CampaignResponseTypeDto
    {
        $payload = $data instanceof CampaignResponseTypeBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($responseType, $payload);

        return CampaignResponseTypeDto::fromResponse($response);
    }

    public function delete(string $responseType): bool
    {
        $this->client()->service($this->entity)->delete($responseType);

        return true;
    }

    /**
     * @return array<CampaignResponseTypeDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => CampaignResponseTypeDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Get active response types.
     *
     * @return array<CampaignResponseTypeDto>
     */
    public function getActive(): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("IsActive eq 'tYES'")
            ->get();

        return array_map(fn (array $item) => CampaignResponseTypeDto::fromResponse($item), $response['value'] ?? []);
    }
}

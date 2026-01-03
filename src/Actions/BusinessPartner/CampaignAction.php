<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\BusinessPartner;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\BusinessPartner\CampaignBuilder;
use SapB1\Toolkit\DTOs\BusinessPartner\CampaignDto;
use SapB1\Toolkit\Enums\CampaignStatus;

/**
 * Campaign actions.
 */
final class CampaignAction extends BaseAction
{
    protected string $entity = 'Campaigns';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $campaignNumber): CampaignDto
    {
        $data = $this->client()->service($this->entity)->find($campaignNumber);

        return CampaignDto::fromResponse($data);
    }

    /**
     * @param  CampaignBuilder|array<string, mixed>  $data
     */
    public function create(CampaignBuilder|array $data): CampaignDto
    {
        $payload = $data instanceof CampaignBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return CampaignDto::fromResponse($response);
    }

    /**
     * @param  CampaignBuilder|array<string, mixed>  $data
     */
    public function update(int $campaignNumber, CampaignBuilder|array $data): CampaignDto
    {
        $payload = $data instanceof CampaignBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($campaignNumber, $payload);

        return CampaignDto::fromResponse($response);
    }

    public function delete(int $campaignNumber): bool
    {
        $this->client()->service($this->entity)->delete($campaignNumber);

        return true;
    }

    /**
     * Cancel a campaign.
     */
    public function cancel(int $campaignNumber): bool
    {
        $this->client()->service($this->entity)->action($campaignNumber, 'CancelCampaign');

        return true;
    }

    /**
     * @return array<CampaignDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => CampaignDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Get active campaigns.
     *
     * @return array<CampaignDto>
     */
    public function getActive(): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("Status eq 'cs_Active'")
            ->get();

        return array_map(fn (array $item) => CampaignDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Get campaigns by status.
     *
     * @return array<CampaignDto>
     */
    public function getByStatus(CampaignStatus $status): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("Status eq '{$status->value}'")
            ->get();

        return array_map(fn (array $item) => CampaignDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Get campaigns by owner.
     *
     * @return array<CampaignDto>
     */
    public function getByOwner(int $owner): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("Owner eq {$owner}")
            ->get();

        return array_map(fn (array $item) => CampaignDto::fromResponse($item), $response['value'] ?? []);
    }
}

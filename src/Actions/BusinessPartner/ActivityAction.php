<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\BusinessPartner;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\BusinessPartner\ActivityBuilder;
use SapB1\Toolkit\DTOs\BusinessPartner\ActivityDto;

/**
 * Activity actions.
 */
final class ActivityAction extends BaseAction
{
    protected string $entity = 'Activities';

    /**
     * @param  int|ActivityBuilder|array<string, mixed>  ...$args
     */
    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        $first = $args[0];

        if (is_int($first)) {
            return $this->find($first);
        }

        return $this->create($first);
    }

    /**
     * Find an activity by ActivityCode.
     */
    public function find(int $activityCode): ActivityDto
    {
        $data = $this->client()
            ->service($this->entity)
            ->find($activityCode);

        return ActivityDto::fromResponse($data);
    }

    /**
     * Create a new activity.
     *
     * @param  ActivityBuilder|array<string, mixed>  $data
     */
    public function create(ActivityBuilder|array $data): ActivityDto
    {
        $payload = $data instanceof ActivityBuilder ? $data->build() : $data;
        $response = $this->client()
            ->service($this->entity)
            ->create($payload);

        return ActivityDto::fromResponse($response);
    }

    /**
     * Update an existing activity.
     *
     * @param  ActivityBuilder|array<string, mixed>  $data
     */
    public function update(int $activityCode, ActivityBuilder|array $data): ActivityDto
    {
        $payload = $data instanceof ActivityBuilder ? $data->build() : $data;
        $response = $this->client()
            ->service($this->entity)
            ->update($activityCode, $payload);

        return ActivityDto::fromResponse($response);
    }

    /**
     * Delete an activity.
     */
    public function delete(int $activityCode): bool
    {
        $this->client()
            ->service($this->entity)
            ->delete($activityCode);

        return true;
    }

    /**
     * Close an activity.
     */
    public function close(int $activityCode): bool
    {
        $this->client()
            ->service($this->entity)
            ->update($activityCode, ['Closed' => 'tYES']);

        return true;
    }

    /**
     * Get activities by business partner.
     *
     * @return array<ActivityDto>
     */
    public function getByBusinessPartner(string $cardCode): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("CardCode eq '{$cardCode}'")
            ->get();

        return array_map(
            fn (array $item) => ActivityDto::fromResponse($item),
            $response['value'] ?? []
        );
    }

    /**
     * Get open activities.
     *
     * @return array<ActivityDto>
     */
    public function getOpen(): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("Closed eq 'tNO'")
            ->get();

        return array_map(
            fn (array $item) => ActivityDto::fromResponse($item),
            $response['value'] ?? []
        );
    }

    /**
     * Get activities by date range.
     *
     * @return array<ActivityDto>
     */
    public function getByDateRange(string $fromDate, string $toDate): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("ActivityDate ge '{$fromDate}' and ActivityDate le '{$toDate}'")
            ->get();

        return array_map(
            fn (array $item) => ActivityDto::fromResponse($item),
            $response['value'] ?? []
        );
    }
}

<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Admin;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\DTOs\Admin\ApprovalRequestDto;
use SapB1\Toolkit\Enums\ApprovalRequestStatusType;

/**
 * Approval Request actions.
 */
final class ApprovalRequestAction extends BaseAction
{
    protected string $entity = 'ApprovalRequests';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return $this->find($args[0]);
    }

    public function find(int $code): ApprovalRequestDto
    {
        $data = $this->client()->service($this->entity)->find($code);

        return ApprovalRequestDto::fromResponse($data);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(int $code, array $data): ApprovalRequestDto
    {
        $response = $this->client()->service($this->entity)->update($code, $data);

        return ApprovalRequestDto::fromResponse($response);
    }

    /**
     * @return array<ApprovalRequestDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => ApprovalRequestDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Get pending approval requests.
     *
     * @return array<ApprovalRequestDto>
     */
    public function getPending(): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("Status eq '".ApprovalRequestStatusType::Pending->value."'")
            ->get();

        return array_map(fn (array $item) => ApprovalRequestDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Get open approval requests.
     *
     * @return array<ApprovalRequestDto>
     */
    public function getOpen(): array
    {
        $data = $this->client()->post('ApprovalRequestsService_GetOpenApprovalRequestList', []);

        return array_map(fn (array $item) => ApprovalRequestDto::fromResponse($item), $data['value'] ?? []);
    }

    /**
     * Get all approval requests.
     *
     * @return array<ApprovalRequestDto>
     */
    public function getAllRequests(): array
    {
        $data = $this->client()->post('ApprovalRequestsService_GetAllApprovalRequestsList', []);

        return array_map(fn (array $item) => ApprovalRequestDto::fromResponse($item), $data['value'] ?? []);
    }

    /**
     * Get requests by originator.
     *
     * @return array<ApprovalRequestDto>
     */
    public function getByOriginator(int $originatorId): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("OriginatorID eq {$originatorId}")
            ->get();

        return array_map(fn (array $item) => ApprovalRequestDto::fromResponse($item), $response['value'] ?? []);
    }
}

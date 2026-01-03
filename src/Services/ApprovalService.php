<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Services;

use SapB1\Toolkit\Enums\ApprovalStatus;

/**
 * Service for approval workflow operations.
 */
final class ApprovalService extends BaseService
{
    /**
     * Get pending approval requests.
     *
     * @return array<array<string, mixed>>
     */
    public function getPendingApprovals(): array
    {
        $response = $this->client()
            ->service('ApprovalRequests')
            ->queryBuilder()
            ->filter("Status eq 'arsApprovalPending'")
            ->get();

        return $response['value'] ?? [];
    }

    /**
     * Get approval requests by status.
     *
     * @return array<array<string, mixed>>
     */
    public function getByStatus(ApprovalStatus $status): array
    {
        $response = $this->client()
            ->service('ApprovalRequests')
            ->queryBuilder()
            ->filter("Status eq '{$status->value}'")
            ->get();

        return $response['value'] ?? [];
    }

    /**
     * Approve a request.
     */
    public function approve(int $approvalRequestCode, ?string $remarks = null): bool
    {
        $data = ['Status' => 'arsApproved'];

        if ($remarks !== null) {
            $data['Remarks'] = $remarks;
        }

        $this->client()
            ->service('ApprovalRequests')
            ->update($approvalRequestCode, $data);

        return true;
    }

    /**
     * Reject a request.
     */
    public function reject(int $approvalRequestCode, ?string $remarks = null): bool
    {
        $data = ['Status' => 'arsRejected'];

        if ($remarks !== null) {
            $data['Remarks'] = $remarks;
        }

        $this->client()
            ->service('ApprovalRequests')
            ->update($approvalRequestCode, $data);

        return true;
    }

    /**
     * Get approval history for a document.
     *
     * @return array<array<string, mixed>>
     */
    public function getDocumentApprovalHistory(int $objectType, int $docEntry): array
    {
        $response = $this->client()
            ->service('ApprovalRequests')
            ->queryBuilder()
            ->filter("ObjectType eq '{$objectType}' and ObjectEntry eq {$docEntry}")
            ->orderBy('ApprovalRequestsLines/ApprovalDateTime desc')
            ->get();

        return $response['value'] ?? [];
    }

    /**
     * Get approvals for current user.
     *
     * @return array<array<string, mixed>>
     */
    public function getMyPendingApprovals(int $userId): array
    {
        $response = $this->client()
            ->service('ApprovalRequests')
            ->queryBuilder()
            ->filter("Status eq 'arsApprovalPending' and ApprovalRequestsLines/any(l: l/UserID eq {$userId})")
            ->get();

        return $response['value'] ?? [];
    }

    /**
     * Get approval templates.
     *
     * @return array<array<string, mixed>>
     */
    public function getApprovalTemplates(): array
    {
        $response = $this->client()
            ->service('ApprovalTemplates')
            ->queryBuilder()
            ->filter("IsActive eq 'tYES'")
            ->get();

        return $response['value'] ?? [];
    }
}

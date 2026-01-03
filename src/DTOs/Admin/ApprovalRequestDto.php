<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Admin;

use SapB1\Toolkit\DTOs\Base\BaseDto;
use SapB1\Toolkit\Enums\ApprovalRequestStatusType;
use SapB1\Toolkit\Enums\BoYesNo;

/**
 * @phpstan-consistent-constructor
 */
final class ApprovalRequestDto extends BaseDto
{
    /**
     * @param  array<ApprovalRequestLineDto>|null  $approvalRequestLines
     * @param  array<ApprovalRequestDecisionDto>|null  $approvalRequestDecisions
     */
    public function __construct(
        public readonly ?int $code = null,
        public readonly ?int $objectType = null,
        public readonly ?BoYesNo $isDraft = null,
        public readonly ?int $objectEntry = null,
        public readonly ?ApprovalRequestStatusType $status = null,
        public readonly ?string $remarks = null,
        public readonly ?int $currentStage = null,
        public readonly ?int $originatorID = null,
        public readonly ?int $approvalTemplateID = null,
        public readonly ?string $creationDate = null,
        public readonly ?string $creationTime = null,
        public readonly ?int $draftEntry = null,
        public readonly ?array $approvalRequestLines = null,
        public readonly ?array $approvalRequestDecisions = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        $lines = null;
        if (isset($data['ApprovalRequestLines']) && is_array($data['ApprovalRequestLines'])) {
            $lines = array_map(
                fn (array $line) => ApprovalRequestLineDto::fromArray($line),
                $data['ApprovalRequestLines']
            );
        }

        $decisions = null;
        if (isset($data['ApprovalRequestDecisions']) && is_array($data['ApprovalRequestDecisions'])) {
            $decisions = array_map(
                fn (array $decision) => ApprovalRequestDecisionDto::fromArray($decision),
                $data['ApprovalRequestDecisions']
            );
        }

        return [
            'code' => $data['Code'] ?? null,
            'objectType' => isset($data['ObjectType']) ? (int) $data['ObjectType'] : null,
            'isDraft' => isset($data['IsDraft']) ? BoYesNo::tryFrom($data['IsDraft']) : null,
            'objectEntry' => $data['ObjectEntry'] ?? null,
            'status' => isset($data['Status']) ? ApprovalRequestStatusType::tryFrom($data['Status']) : null,
            'remarks' => $data['Remarks'] ?? null,
            'currentStage' => $data['CurrentStage'] ?? null,
            'originatorID' => $data['OriginatorID'] ?? null,
            'approvalTemplateID' => $data['ApprovalTemplateID'] ?? null,
            'creationDate' => $data['CreationDate'] ?? null,
            'creationTime' => $data['CreationTime'] ?? null,
            'draftEntry' => $data['DraftEntry'] ?? null,
            'approvalRequestLines' => $lines,
            'approvalRequestDecisions' => $decisions,
        ];
    }

    public function toArray(): array
    {
        $result = array_filter([
            'Code' => $this->code,
            'ObjectType' => $this->objectType !== null ? (string) $this->objectType : null,
            'IsDraft' => $this->isDraft?->value,
            'ObjectEntry' => $this->objectEntry,
            'Status' => $this->status?->value,
            'Remarks' => $this->remarks,
            'CurrentStage' => $this->currentStage,
            'OriginatorID' => $this->originatorID,
            'ApprovalTemplateID' => $this->approvalTemplateID,
            'CreationDate' => $this->creationDate,
            'CreationTime' => $this->creationTime,
            'DraftEntry' => $this->draftEntry,
        ], fn ($value) => $value !== null);

        if ($this->approvalRequestLines !== null) {
            $result['ApprovalRequestLines'] = array_map(
                fn (ApprovalRequestLineDto $line) => $line->toArray(),
                $this->approvalRequestLines
            );
        }

        if ($this->approvalRequestDecisions !== null) {
            $result['ApprovalRequestDecisions'] = array_map(
                fn (ApprovalRequestDecisionDto $decision) => $decision->toArray(),
                $this->approvalRequestDecisions
            );
        }

        return $result;
    }

    public function isPending(): bool
    {
        return $this->status?->isPending() ?? false;
    }

    public function isApproved(): bool
    {
        return $this->status?->isApproved() ?? false;
    }
}

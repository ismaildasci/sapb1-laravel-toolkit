<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Admin;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class ApprovalStageDto extends BaseDto
{
    /**
     * @param  array<ApprovalStageApproverDto>|null  $approvalStageApprovers
     */
    public function __construct(
        public readonly ?int $code = null,
        public readonly ?string $name = null,
        public readonly ?int $noOfApproversRequired = null,
        public readonly ?string $remarks = null,
        public readonly ?array $approvalStageApprovers = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        $approvers = null;
        if (isset($data['ApprovalStageApprovers']) && is_array($data['ApprovalStageApprovers'])) {
            $approvers = array_map(
                fn (array $approver) => ApprovalStageApproverDto::fromArray($approver),
                $data['ApprovalStageApprovers']
            );
        }

        return [
            'code' => $data['Code'] ?? null,
            'name' => $data['Name'] ?? null,
            'noOfApproversRequired' => $data['NoOfApproversRequired'] ?? null,
            'remarks' => $data['Remarks'] ?? null,
            'approvalStageApprovers' => $approvers,
        ];
    }

    public function toArray(): array
    {
        $result = array_filter([
            'Code' => $this->code,
            'Name' => $this->name,
            'NoOfApproversRequired' => $this->noOfApproversRequired,
            'Remarks' => $this->remarks,
        ], fn ($value) => $value !== null);

        if ($this->approvalStageApprovers !== null) {
            $result['ApprovalStageApprovers'] = array_map(
                fn (ApprovalStageApproverDto $approver) => $approver->toArray(),
                $this->approvalStageApprovers
            );
        }

        return $result;
    }
}

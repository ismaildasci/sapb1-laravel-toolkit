<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Admin;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class ApprovalTemplateStageDto extends BaseDto
{
    public function __construct(
        public readonly ?int $sortID = null,
        public readonly ?int $approvalStageCode = null,
        public readonly ?string $remarks = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'sortID' => $data['SortID'] ?? null,
            'approvalStageCode' => $data['ApprovalStageCode'] ?? null,
            'remarks' => $data['Remarks'] ?? null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'SortID' => $this->sortID,
            'ApprovalStageCode' => $this->approvalStageCode,
            'Remarks' => $this->remarks,
        ], fn ($value) => $value !== null);
    }
}

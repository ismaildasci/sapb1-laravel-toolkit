<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Admin;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class ApprovalRequestDecisionDto extends BaseDto
{
    public function __construct(
        public readonly ?int $approverUserName = null,
        public readonly ?int $approverPassword = null,
        public readonly ?string $status = null,
        public readonly ?string $remarks = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'approverUserName' => $data['ApproverUserName'] ?? null,
            'approverPassword' => $data['ApproverPassword'] ?? null,
            'status' => $data['Status'] ?? null,
            'remarks' => $data['Remarks'] ?? null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'ApproverUserName' => $this->approverUserName,
            'ApproverPassword' => $this->approverPassword,
            'Status' => $this->status,
            'Remarks' => $this->remarks,
        ], fn ($value) => $value !== null);
    }
}

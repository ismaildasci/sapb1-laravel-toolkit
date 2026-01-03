<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Admin;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class ApprovalRequestLineDto extends BaseDto
{
    public function __construct(
        public readonly ?int $stageCode = null,
        public readonly ?int $userID = null,
        public readonly ?string $status = null,
        public readonly ?string $remarks = null,
        public readonly ?string $updateDate = null,
        public readonly ?string $updateTime = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'stageCode' => $data['StageCode'] ?? null,
            'userID' => $data['UserID'] ?? null,
            'status' => $data['Status'] ?? null,
            'remarks' => $data['Remarks'] ?? null,
            'updateDate' => $data['UpdateDate'] ?? null,
            'updateTime' => $data['UpdateTime'] ?? null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'StageCode' => $this->stageCode,
            'UserID' => $this->userID,
            'Status' => $this->status,
            'Remarks' => $this->remarks,
            'UpdateDate' => $this->updateDate,
            'UpdateTime' => $this->updateTime,
        ], fn ($value) => $value !== null);
    }
}

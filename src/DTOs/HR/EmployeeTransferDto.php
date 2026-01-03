<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\HR;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class EmployeeTransferDto extends BaseDto
{
    public function __construct(
        public readonly ?int $transferID = null,
        public readonly ?string $transferDate = null,
        public readonly ?int $employeeID = null,
        public readonly ?int $fromDepartment = null,
        public readonly ?int $toDepartment = null,
        public readonly ?int $fromBranch = null,
        public readonly ?int $toBranch = null,
        public readonly ?int $fromPosition = null,
        public readonly ?int $toPosition = null,
        public readonly ?int $fromManager = null,
        public readonly ?int $toManager = null,
        public readonly ?string $comment = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'transferID' => $data['TransferID'] ?? null,
            'transferDate' => $data['TransferDate'] ?? null,
            'employeeID' => $data['EmployeeID'] ?? null,
            'fromDepartment' => $data['FromDepartment'] ?? null,
            'toDepartment' => $data['ToDepartment'] ?? null,
            'fromBranch' => $data['FromBranch'] ?? null,
            'toBranch' => $data['ToBranch'] ?? null,
            'fromPosition' => $data['FromPosition'] ?? null,
            'toPosition' => $data['ToPosition'] ?? null,
            'fromManager' => $data['FromManager'] ?? null,
            'toManager' => $data['ToManager'] ?? null,
            'comment' => $data['Comment'] ?? null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'TransferID' => $this->transferID,
            'TransferDate' => $this->transferDate,
            'EmployeeID' => $this->employeeID,
            'FromDepartment' => $this->fromDepartment,
            'ToDepartment' => $this->toDepartment,
            'FromBranch' => $this->fromBranch,
            'ToBranch' => $this->toBranch,
            'FromPosition' => $this->fromPosition,
            'ToPosition' => $this->toPosition,
            'FromManager' => $this->fromManager,
            'ToManager' => $this->toManager,
            'Comment' => $this->comment,
        ], fn ($value) => $value !== null);
    }
}

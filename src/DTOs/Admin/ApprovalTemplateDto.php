<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Admin;

use SapB1\Toolkit\DTOs\Base\BaseDto;
use SapB1\Toolkit\Enums\BoYesNo;

/**
 * @phpstan-consistent-constructor
 */
final class ApprovalTemplateDto extends BaseDto
{
    /**
     * @param  array<ApprovalTemplateStageDto>|null  $approvalTemplateStages
     * @param  array<ApprovalTemplateUserDto>|null  $approvalTemplateUsers
     * @param  array<ApprovalTemplateDocumentDto>|null  $approvalTemplateDocuments
     */
    public function __construct(
        public readonly ?int $code = null,
        public readonly ?string $name = null,
        public readonly ?string $remarks = null,
        public readonly ?BoYesNo $isActive = null,
        public readonly ?BoYesNo $isActiveWhenUpdating = null,
        public readonly ?array $approvalTemplateStages = null,
        public readonly ?array $approvalTemplateUsers = null,
        public readonly ?array $approvalTemplateDocuments = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        $stages = null;
        if (isset($data['ApprovalTemplateStages']) && is_array($data['ApprovalTemplateStages'])) {
            $stages = array_map(
                fn (array $stage) => ApprovalTemplateStageDto::fromArray($stage),
                $data['ApprovalTemplateStages']
            );
        }

        $users = null;
        if (isset($data['ApprovalTemplateUsers']) && is_array($data['ApprovalTemplateUsers'])) {
            $users = array_map(
                fn (array $user) => ApprovalTemplateUserDto::fromArray($user),
                $data['ApprovalTemplateUsers']
            );
        }

        $documents = null;
        if (isset($data['ApprovalTemplateDocuments']) && is_array($data['ApprovalTemplateDocuments'])) {
            $documents = array_map(
                fn (array $doc) => ApprovalTemplateDocumentDto::fromArray($doc),
                $data['ApprovalTemplateDocuments']
            );
        }

        return [
            'code' => $data['Code'] ?? null,
            'name' => $data['Name'] ?? null,
            'remarks' => $data['Remarks'] ?? null,
            'isActive' => isset($data['IsActive']) ? BoYesNo::tryFrom($data['IsActive']) : null,
            'isActiveWhenUpdating' => isset($data['IsActiveWhenUpdatingDocuments'])
                ? BoYesNo::tryFrom($data['IsActiveWhenUpdatingDocuments'])
                : null,
            'approvalTemplateStages' => $stages,
            'approvalTemplateUsers' => $users,
            'approvalTemplateDocuments' => $documents,
        ];
    }

    public function toArray(): array
    {
        $result = array_filter([
            'Code' => $this->code,
            'Name' => $this->name,
            'Remarks' => $this->remarks,
            'IsActive' => $this->isActive?->value,
            'IsActiveWhenUpdatingDocuments' => $this->isActiveWhenUpdating?->value,
        ], fn ($value) => $value !== null);

        if ($this->approvalTemplateStages !== null) {
            $result['ApprovalTemplateStages'] = array_map(
                fn (ApprovalTemplateStageDto $stage) => $stage->toArray(),
                $this->approvalTemplateStages
            );
        }

        if ($this->approvalTemplateUsers !== null) {
            $result['ApprovalTemplateUsers'] = array_map(
                fn (ApprovalTemplateUserDto $user) => $user->toArray(),
                $this->approvalTemplateUsers
            );
        }

        if ($this->approvalTemplateDocuments !== null) {
            $result['ApprovalTemplateDocuments'] = array_map(
                fn (ApprovalTemplateDocumentDto $doc) => $doc->toArray(),
                $this->approvalTemplateDocuments
            );
        }

        return $result;
    }
}

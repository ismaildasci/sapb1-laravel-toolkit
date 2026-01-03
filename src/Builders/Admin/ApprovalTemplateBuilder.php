<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Admin;

use SapB1\Toolkit\Builders\BaseBuilder;
use SapB1\Toolkit\Enums\BoYesNo;

/**
 * @phpstan-consistent-constructor
 */
final class ApprovalTemplateBuilder extends BaseBuilder
{
    public function name(string $name): static
    {
        return $this->set('Name', $name);
    }

    public function remarks(string $remarks): static
    {
        return $this->set('Remarks', $remarks);
    }

    public function isActive(BoYesNo $active): static
    {
        return $this->set('IsActive', $active->value);
    }

    public function isActiveWhenUpdating(BoYesNo $active): static
    {
        return $this->set('IsActiveWhenUpdatingDocuments', $active->value);
    }

    /**
     * @param  array<ApprovalTemplateStageBuilder|array<string, mixed>>  $stages
     */
    public function approvalTemplateStages(array $stages): static
    {
        $builtStages = array_map(
            fn ($stage) => $stage instanceof ApprovalTemplateStageBuilder ? $stage->build() : $stage,
            $stages
        );

        return $this->set('ApprovalTemplateStages', $builtStages);
    }

    /**
     * @param  array<ApprovalTemplateUserBuilder|array<string, mixed>>  $users
     */
    public function approvalTemplateUsers(array $users): static
    {
        $builtUsers = array_map(
            fn ($user) => $user instanceof ApprovalTemplateUserBuilder ? $user->build() : $user,
            $users
        );

        return $this->set('ApprovalTemplateUsers', $builtUsers);
    }

    /**
     * @param  array<ApprovalTemplateDocumentBuilder|array<string, mixed>>  $documents
     */
    public function approvalTemplateDocuments(array $documents): static
    {
        $builtDocs = array_map(
            fn ($doc) => $doc instanceof ApprovalTemplateDocumentBuilder ? $doc->build() : $doc,
            $documents
        );

        return $this->set('ApprovalTemplateDocuments', $builtDocs);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

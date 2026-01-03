<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Admin;

use SapB1\Toolkit\Builders\BaseBuilder;

/**
 * @phpstan-consistent-constructor
 */
final class ApprovalStageBuilder extends BaseBuilder
{
    public function name(string $name): static
    {
        return $this->set('Name', $name);
    }

    public function noOfApproversRequired(int $count): static
    {
        return $this->set('NoOfApproversRequired', $count);
    }

    public function remarks(string $remarks): static
    {
        return $this->set('Remarks', $remarks);
    }

    /**
     * @param  array<ApprovalStageApproverBuilder|array<string, mixed>>  $approvers
     */
    public function approvalStageApprovers(array $approvers): static
    {
        $builtApprovers = array_map(
            fn ($approver) => $approver instanceof ApprovalStageApproverBuilder ? $approver->build() : $approver,
            $approvers
        );

        return $this->set('ApprovalStageApprovers', $builtApprovers);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

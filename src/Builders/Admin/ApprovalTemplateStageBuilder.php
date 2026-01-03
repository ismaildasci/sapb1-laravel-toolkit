<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Admin;

use SapB1\Toolkit\Builders\BaseBuilder;

/**
 * @phpstan-consistent-constructor
 */
final class ApprovalTemplateStageBuilder extends BaseBuilder
{
    public function sortID(int $sortId): static
    {
        return $this->set('SortID', $sortId);
    }

    public function approvalStageCode(int $stageCode): static
    {
        return $this->set('ApprovalStageCode', $stageCode);
    }

    public function remarks(string $remarks): static
    {
        return $this->set('Remarks', $remarks);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

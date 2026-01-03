<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Concerns;

trait HasApproval
{
    protected bool $requiresApproval = false;

    /**
     * @var array<int, array<string, mixed>>
     */
    protected array $approvalTemplates = [];

    public function requiresApproval(): bool
    {
        return $this->requiresApproval;
    }

    /**
     * @param  array<int, array<string, mixed>>  $templates
     */
    public function setApprovalTemplates(array $templates): static
    {
        $this->approvalTemplates = $templates;
        $this->requiresApproval = count($templates) > 0;

        return $this;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getApprovalTemplates(): array
    {
        return $this->approvalTemplates;
    }

    public function isUnderApproval(): bool
    {
        return $this->requiresApproval && count($this->approvalTemplates) > 0;
    }
}

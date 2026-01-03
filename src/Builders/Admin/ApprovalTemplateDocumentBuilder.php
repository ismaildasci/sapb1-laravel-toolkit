<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Admin;

use SapB1\Toolkit\Builders\BaseBuilder;

/**
 * @phpstan-consistent-constructor
 */
final class ApprovalTemplateDocumentBuilder extends BaseBuilder
{
    public function documentType(int $docType): static
    {
        return $this->set('DocumentType', (string) $docType);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Admin;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class ApprovalTemplateDocumentDto extends BaseDto
{
    public function __construct(
        public readonly ?int $documentType = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'documentType' => isset($data['DocumentType']) ? (int) $data['DocumentType'] : null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'DocumentType' => $this->documentType !== null ? (string) $this->documentType : null,
        ], fn ($value) => $value !== null);
    }
}

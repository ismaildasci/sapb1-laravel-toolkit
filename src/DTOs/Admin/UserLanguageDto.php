<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Admin;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class UserLanguageDto extends BaseDto
{
    public function __construct(
        public readonly ?int $code = null,
        public readonly ?int $languageShortName = null,
        public readonly ?string $languageFullName = null,
        public readonly ?string $relatedSystemLanguage = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'code' => $data['Code'] ?? null,
            'languageShortName' => $data['LanguageShortName'] ?? null,
            'languageFullName' => $data['LanguageFullName'] ?? null,
            'relatedSystemLanguage' => $data['RelatedSystemLanguage'] ?? null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'Code' => $this->code,
            'LanguageShortName' => $this->languageShortName,
            'LanguageFullName' => $this->languageFullName,
            'RelatedSystemLanguage' => $this->relatedSystemLanguage,
        ], fn ($value) => $value !== null);
    }
}

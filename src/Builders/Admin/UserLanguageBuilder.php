<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Admin;

use SapB1\Toolkit\Builders\BaseBuilder;

/**
 * @phpstan-consistent-constructor
 */
final class UserLanguageBuilder extends BaseBuilder
{
    public function languageShortName(int $shortName): static
    {
        return $this->set('LanguageShortName', $shortName);
    }

    public function languageFullName(string $fullName): static
    {
        return $this->set('LanguageFullName', $fullName);
    }

    public function relatedSystemLanguage(string $language): static
    {
        return $this->set('RelatedSystemLanguage', $language);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

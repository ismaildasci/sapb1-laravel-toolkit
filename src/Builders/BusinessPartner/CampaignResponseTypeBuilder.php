<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\BusinessPartner;

use SapB1\Toolkit\Builders\BaseBuilder;
use SapB1\Toolkit\Enums\BoYesNo;

/**
 * @phpstan-consistent-constructor
 */
final class CampaignResponseTypeBuilder extends BaseBuilder
{
    public function responseType(string $type): static
    {
        return $this->set('ResponseType', $type);
    }

    public function responseTypeDescription(string $description): static
    {
        return $this->set('ResponseTypeDescription', $description);
    }

    public function isActive(BoYesNo $active): static
    {
        return $this->set('IsActive', $active->value);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\BusinessPartner;

use SapB1\Toolkit\Builders\BaseBuilder;
use SapB1\Toolkit\DTOs\BusinessPartner\CampaignItemDto;
use SapB1\Toolkit\Enums\CampaignStatus;

/**
 * @phpstan-consistent-constructor
 */
final class CampaignBuilder extends BaseBuilder
{
    public function campaignName(string $name): static
    {
        return $this->set('CampaignName', $name);
    }

    public function campaignType(string $type): static
    {
        return $this->set('CampaignType', $type);
    }

    public function targetGroup(string $group): static
    {
        return $this->set('TargetGroup', $group);
    }

    public function owner(int $owner): static
    {
        return $this->set('Owner', $owner);
    }

    public function status(CampaignStatus $status): static
    {
        return $this->set('Status', $status->value);
    }

    public function startDate(string $date): static
    {
        return $this->set('StartDate', $date);
    }

    public function finishDate(string $date): static
    {
        return $this->set('FinishDate', $date);
    }

    public function remarks(string $remarks): static
    {
        return $this->set('Remarks', $remarks);
    }

    public function attachedDocumentPath(string $path): static
    {
        return $this->set('AttachedDocumentPath', $path);
    }

    /**
     * @param  array<CampaignItemDto|array<string, mixed>>  $items
     */
    public function campaignBusinessPartners(array $items): static
    {
        $mapped = array_map(
            fn ($item) => $item instanceof CampaignItemDto ? $item->toArray() : $item,
            $items
        );

        return $this->set('CampaignBusinessPartners', $mapped);
    }

    /**
     * @param  CampaignItemDto|array<string, mixed>  $item
     */
    public function addBusinessPartner(CampaignItemDto|array $item): static
    {
        $items = $this->get('CampaignBusinessPartners', []);
        $items[] = $item instanceof CampaignItemDto ? $item->toArray() : $item;

        return $this->set('CampaignBusinessPartners', $items);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

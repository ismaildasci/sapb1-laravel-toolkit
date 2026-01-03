<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\BusinessPartner;

use SapB1\Toolkit\DTOs\Base\BaseDto;
use SapB1\Toolkit\Enums\CampaignStatus;

/**
 * @phpstan-consistent-constructor
 */
final class CampaignDto extends BaseDto
{
    /**
     * @param  array<CampaignItemDto>  $campaignBusinessPartners
     */
    public function __construct(
        public readonly ?int $campaignNumber = null,
        public readonly ?string $campaignName = null,
        public readonly ?string $campaignType = null,
        public readonly ?string $targetGroup = null,
        public readonly ?int $owner = null,
        public readonly ?CampaignStatus $status = null,
        public readonly ?string $startDate = null,
        public readonly ?string $finishDate = null,
        public readonly ?string $remarks = null,
        public readonly ?string $attachedDocumentPath = null,
        public readonly array $campaignBusinessPartners = [],
    ) {}

    protected static function mapFromArray(array $data): array
    {
        $status = null;
        if (isset($data['Status'])) {
            $status = CampaignStatus::tryFrom($data['Status']);
        }

        $items = [];
        if (isset($data['CampaignBusinessPartners']) && is_array($data['CampaignBusinessPartners'])) {
            foreach ($data['CampaignBusinessPartners'] as $item) {
                $items[] = CampaignItemDto::fromArray($item);
            }
        }

        return [
            'campaignNumber' => $data['CampaignNumber'] ?? null,
            'campaignName' => $data['CampaignName'] ?? null,
            'campaignType' => $data['CampaignType'] ?? null,
            'targetGroup' => $data['TargetGroup'] ?? null,
            'owner' => $data['Owner'] ?? null,
            'status' => $status,
            'startDate' => $data['StartDate'] ?? null,
            'finishDate' => $data['FinishDate'] ?? null,
            'remarks' => $data['Remarks'] ?? null,
            'attachedDocumentPath' => $data['AttachedDocumentPath'] ?? null,
            'campaignBusinessPartners' => $items,
        ];
    }

    public function toArray(): array
    {
        $data = array_filter([
            'CampaignNumber' => $this->campaignNumber,
            'CampaignName' => $this->campaignName,
            'CampaignType' => $this->campaignType,
            'TargetGroup' => $this->targetGroup,
            'Owner' => $this->owner,
            'Status' => $this->status?->value,
            'StartDate' => $this->startDate,
            'FinishDate' => $this->finishDate,
            'Remarks' => $this->remarks,
            'AttachedDocumentPath' => $this->attachedDocumentPath,
        ], fn ($value) => $value !== null);

        if (! empty($this->campaignBusinessPartners)) {
            $data['CampaignBusinessPartners'] = array_map(
                fn (CampaignItemDto $item) => $item->toArray(),
                $this->campaignBusinessPartners
            );
        }

        return $data;
    }
}

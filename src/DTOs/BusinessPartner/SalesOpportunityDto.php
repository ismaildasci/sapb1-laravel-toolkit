<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\BusinessPartner;

use SapB1\Toolkit\DTOs\Base\BaseDto;
use SapB1\Toolkit\Enums\OpportunityStatus;

/**
 * @phpstan-consistent-constructor
 */
final class SalesOpportunityDto extends BaseDto
{
    /**
     * @param  array<SalesOpportunityLineDto>  $salesOpportunitiesLines
     */
    public function __construct(
        public readonly ?int $sequentialNo = null,
        public readonly ?string $cardCode = null,
        public readonly ?int $salesPerson = null,
        public readonly ?int $contactPerson = null,
        public readonly ?int $source = null,
        public readonly ?int $interestField1 = null,
        public readonly ?int $interestField2 = null,
        public readonly ?int $interestField3 = null,
        public readonly ?int $interestLevel = null,
        public readonly ?string $startDate = null,
        public readonly ?string $closingDate = null,
        public readonly ?string $dataOwnershipfield = null,
        public readonly ?OpportunityStatus $status = null,
        public readonly ?string $statusRemarks = null,
        public readonly ?string $reasonForClosing = null,
        public readonly ?float $totalAmountLocal = null,
        public readonly ?float $totalAmountSystem = null,
        public readonly ?string $closingGrossProfit = null,
        public readonly ?string $closingPercentage = null,
        public readonly ?string $currentStageNo = null,
        public readonly ?string $currentStageNumber = null,
        public readonly ?string $opportunityName = null,
        public readonly ?int $industry = null,
        public readonly ?string $linkedDocumentType = null,
        public readonly ?int $linkedDocumentEntry = null,
        public readonly ?string $remarks = null,
        public readonly array $salesOpportunitiesLines = [],
    ) {}

    protected static function mapFromArray(array $data): array
    {
        $status = null;
        if (isset($data['Status'])) {
            $status = OpportunityStatus::tryFrom($data['Status']);
        }

        $lines = [];
        if (isset($data['SalesOpportunitiesLines']) && is_array($data['SalesOpportunitiesLines'])) {
            foreach ($data['SalesOpportunitiesLines'] as $line) {
                $lines[] = SalesOpportunityLineDto::fromArray($line);
            }
        }

        return [
            'sequentialNo' => $data['SequentialNo'] ?? null,
            'cardCode' => $data['CardCode'] ?? null,
            'salesPerson' => $data['SalesPerson'] ?? null,
            'contactPerson' => $data['ContactPerson'] ?? null,
            'source' => $data['Source'] ?? null,
            'interestField1' => $data['InterestField1'] ?? null,
            'interestField2' => $data['InterestField2'] ?? null,
            'interestField3' => $data['InterestField3'] ?? null,
            'interestLevel' => $data['InterestLevel'] ?? null,
            'startDate' => $data['StartDate'] ?? null,
            'closingDate' => $data['ClosingDate'] ?? null,
            'dataOwnershipfield' => $data['DataOwnershipfield'] ?? null,
            'status' => $status,
            'statusRemarks' => $data['StatusRemarks'] ?? null,
            'reasonForClosing' => $data['ReasonForClosing'] ?? null,
            'totalAmountLocal' => isset($data['TotalAmountLocal']) ? (float) $data['TotalAmountLocal'] : null,
            'totalAmountSystem' => isset($data['TotalAmountSystem']) ? (float) $data['TotalAmountSystem'] : null,
            'closingGrossProfit' => $data['ClosingGrossProfit'] ?? null,
            'closingPercentage' => $data['ClosingPercentage'] ?? null,
            'currentStageNo' => $data['CurrentStageNo'] ?? null,
            'currentStageNumber' => $data['CurrentStageNumber'] ?? null,
            'opportunityName' => $data['OpportunityName'] ?? null,
            'industry' => $data['Industry'] ?? null,
            'linkedDocumentType' => $data['LinkedDocumentType'] ?? null,
            'linkedDocumentEntry' => $data['LinkedDocumentEntry'] ?? null,
            'remarks' => $data['Remarks'] ?? null,
            'salesOpportunitiesLines' => $lines,
        ];
    }

    public function toArray(): array
    {
        $data = array_filter([
            'SequentialNo' => $this->sequentialNo,
            'CardCode' => $this->cardCode,
            'SalesPerson' => $this->salesPerson,
            'ContactPerson' => $this->contactPerson,
            'Source' => $this->source,
            'InterestField1' => $this->interestField1,
            'InterestField2' => $this->interestField2,
            'InterestField3' => $this->interestField3,
            'InterestLevel' => $this->interestLevel,
            'StartDate' => $this->startDate,
            'ClosingDate' => $this->closingDate,
            'DataOwnershipfield' => $this->dataOwnershipfield,
            'Status' => $this->status?->value,
            'StatusRemarks' => $this->statusRemarks,
            'ReasonForClosing' => $this->reasonForClosing,
            'TotalAmountLocal' => $this->totalAmountLocal,
            'TotalAmountSystem' => $this->totalAmountSystem,
            'ClosingGrossProfit' => $this->closingGrossProfit,
            'ClosingPercentage' => $this->closingPercentage,
            'CurrentStageNo' => $this->currentStageNo,
            'CurrentStageNumber' => $this->currentStageNumber,
            'OpportunityName' => $this->opportunityName,
            'Industry' => $this->industry,
            'LinkedDocumentType' => $this->linkedDocumentType,
            'LinkedDocumentEntry' => $this->linkedDocumentEntry,
            'Remarks' => $this->remarks,
        ], fn ($value) => $value !== null);

        if (! empty($this->salesOpportunitiesLines)) {
            $data['SalesOpportunitiesLines'] = array_map(
                fn (SalesOpportunityLineDto $line) => $line->toArray(),
                $this->salesOpportunitiesLines
            );
        }

        return $data;
    }
}

<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\BusinessPartner;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class SalesOpportunityLineDto extends BaseDto
{
    public function __construct(
        public readonly ?int $lineNum = null,
        public readonly ?int $salesPerson = null,
        public readonly ?string $startDate = null,
        public readonly ?string $closingDate = null,
        public readonly ?int $stageKey = null,
        public readonly ?float $percentageRate = null,
        public readonly ?float $maxLocalTotal = null,
        public readonly ?float $maxSystemTotal = null,
        public readonly ?string $remarks = null,
        public readonly ?string $contact = null,
        public readonly ?string $status = null,
        public readonly ?float $weightedSumLC = null,
        public readonly ?float $weightedSumSC = null,
        public readonly ?int $documentNumber = null,
        public readonly ?string $documentType = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'lineNum' => $data['LineNum'] ?? null,
            'salesPerson' => $data['SalesPerson'] ?? null,
            'startDate' => $data['StartDate'] ?? null,
            'closingDate' => $data['ClosingDate'] ?? null,
            'stageKey' => $data['StageKey'] ?? null,
            'percentageRate' => isset($data['PercentageRate']) ? (float) $data['PercentageRate'] : null,
            'maxLocalTotal' => isset($data['MaxLocalTotal']) ? (float) $data['MaxLocalTotal'] : null,
            'maxSystemTotal' => isset($data['MaxSystemTotal']) ? (float) $data['MaxSystemTotal'] : null,
            'remarks' => $data['Remarks'] ?? null,
            'contact' => $data['Contact'] ?? null,
            'status' => $data['Status'] ?? null,
            'weightedSumLC' => isset($data['WeightedSumLC']) ? (float) $data['WeightedSumLC'] : null,
            'weightedSumSC' => isset($data['WeightedSumSC']) ? (float) $data['WeightedSumSC'] : null,
            'documentNumber' => $data['DocumentNumber'] ?? null,
            'documentType' => $data['DocumentType'] ?? null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'LineNum' => $this->lineNum,
            'SalesPerson' => $this->salesPerson,
            'StartDate' => $this->startDate,
            'ClosingDate' => $this->closingDate,
            'StageKey' => $this->stageKey,
            'PercentageRate' => $this->percentageRate,
            'MaxLocalTotal' => $this->maxLocalTotal,
            'MaxSystemTotal' => $this->maxSystemTotal,
            'Remarks' => $this->remarks,
            'Contact' => $this->contact,
            'Status' => $this->status,
            'WeightedSumLC' => $this->weightedSumLC,
            'WeightedSumSC' => $this->weightedSumSC,
            'DocumentNumber' => $this->documentNumber,
            'DocumentType' => $this->documentType,
        ], fn ($value) => $value !== null);
    }
}

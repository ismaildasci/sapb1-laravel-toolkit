<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Production;

use SapB1\Toolkit\DTOs\Base\BaseDto;
use SapB1\Toolkit\Enums\IssueMethod;

/**
 * @phpstan-consistent-constructor
 */
final class ProductionOrderLineDto extends BaseDto
{
    public function __construct(
        public readonly ?int $lineNumber = null,
        public readonly ?string $itemNo = null,
        public readonly ?float $baseQuantity = null,
        public readonly ?float $plannedQuantity = null,
        public readonly ?float $issuedQuantity = null,
        public readonly ?string $warehouse = null,
        public readonly ?IssueMethod $issueMethod = null,
        public readonly ?string $productionOrderIssueType = null,
        public readonly ?float $additionalQuantity = null,
        public readonly ?string $resourceAllocation = null,
        public readonly ?string $startDate = null,
        public readonly ?string $endDate = null,
        public readonly ?string $stagingAreaCode = null,
        public readonly ?int $project = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'lineNumber' => $data['LineNumber'] ?? null,
            'itemNo' => $data['ItemNo'] ?? null,
            'baseQuantity' => isset($data['BaseQuantity']) ? (float) $data['BaseQuantity'] : null,
            'plannedQuantity' => isset($data['PlannedQuantity']) ? (float) $data['PlannedQuantity'] : null,
            'issuedQuantity' => isset($data['IssuedQuantity']) ? (float) $data['IssuedQuantity'] : null,
            'warehouse' => $data['Warehouse'] ?? null,
            'issueMethod' => isset($data['IssueMethod']) ? IssueMethod::tryFrom($data['IssueMethod']) : null,
            'productionOrderIssueType' => $data['ProductionOrderIssueType'] ?? null,
            'additionalQuantity' => isset($data['AdditionalQuantity']) ? (float) $data['AdditionalQuantity'] : null,
            'resourceAllocation' => $data['ResourceAllocation'] ?? null,
            'startDate' => $data['StartDate'] ?? null,
            'endDate' => $data['EndDate'] ?? null,
            'stagingAreaCode' => $data['StagingAreaCode'] ?? null,
            'project' => $data['Project'] ?? null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'LineNumber' => $this->lineNumber,
            'ItemNo' => $this->itemNo,
            'BaseQuantity' => $this->baseQuantity,
            'PlannedQuantity' => $this->plannedQuantity,
            'IssuedQuantity' => $this->issuedQuantity,
            'Warehouse' => $this->warehouse,
            'IssueMethod' => $this->issueMethod?->value,
            'ProductionOrderIssueType' => $this->productionOrderIssueType,
            'AdditionalQuantity' => $this->additionalQuantity,
            'ResourceAllocation' => $this->resourceAllocation,
            'StartDate' => $this->startDate,
            'EndDate' => $this->endDate,
            'StagingAreaCode' => $this->stagingAreaCode,
            'Project' => $this->project,
        ], fn ($value) => $value !== null);
    }
}

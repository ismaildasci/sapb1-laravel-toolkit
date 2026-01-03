<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Production;

use SapB1\Toolkit\DTOs\Base\BaseDto;
use SapB1\Toolkit\Enums\ProductionOrderStatus;
use SapB1\Toolkit\Enums\ProductionOrderType;

/**
 * @phpstan-consistent-constructor
 */
final class ProductionOrderDto extends BaseDto
{
    /**
     * @param  array<ProductionOrderLineDto>|null  $productionOrderLines
     */
    public function __construct(
        public readonly ?int $absoluteEntry = null,
        public readonly ?int $documentNumber = null,
        public readonly ?int $series = null,
        public readonly ?string $itemNo = null,
        public readonly ?ProductionOrderStatus $productionOrderStatus = null,
        public readonly ?ProductionOrderType $productionOrderType = null,
        public readonly ?float $plannedQuantity = null,
        public readonly ?float $completedQuantity = null,
        public readonly ?float $rejectedQuantity = null,
        public readonly ?string $postingDate = null,
        public readonly ?string $dueDate = null,
        public readonly ?string $productionOrderOriginEntry = null,
        public readonly ?string $productionOrderOriginNumber = null,
        public readonly ?string $warehouse = null,
        public readonly ?int $productionBillOfMaterial = null,
        public readonly ?string $project = null,
        public readonly ?string $remarks = null,
        public readonly ?string $customer = null,
        public readonly ?int $priority = null,
        public readonly ?string $startDate = null,
        public readonly ?string $closingDate = null,
        public readonly ?string $releaseDate = null,
        public readonly ?array $productionOrderLines = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        $lines = null;
        if (isset($data['ProductionOrderLines']) && is_array($data['ProductionOrderLines'])) {
            $lines = array_map(
                fn (array $line) => ProductionOrderLineDto::fromArray($line),
                $data['ProductionOrderLines']
            );
        }

        return [
            'absoluteEntry' => $data['AbsoluteEntry'] ?? null,
            'documentNumber' => $data['DocumentNumber'] ?? null,
            'series' => $data['Series'] ?? null,
            'itemNo' => $data['ItemNo'] ?? null,
            'productionOrderStatus' => isset($data['ProductionOrderStatus'])
                ? ProductionOrderStatus::tryFrom($data['ProductionOrderStatus'])
                : null,
            'productionOrderType' => isset($data['ProductionOrderType'])
                ? ProductionOrderType::tryFrom($data['ProductionOrderType'])
                : null,
            'plannedQuantity' => isset($data['PlannedQuantity']) ? (float) $data['PlannedQuantity'] : null,
            'completedQuantity' => isset($data['CompletedQuantity']) ? (float) $data['CompletedQuantity'] : null,
            'rejectedQuantity' => isset($data['RejectedQuantity']) ? (float) $data['RejectedQuantity'] : null,
            'postingDate' => $data['PostingDate'] ?? null,
            'dueDate' => $data['DueDate'] ?? null,
            'productionOrderOriginEntry' => isset($data['ProductionOrderOriginEntry'])
                ? (string) $data['ProductionOrderOriginEntry']
                : null,
            'productionOrderOriginNumber' => isset($data['ProductionOrderOriginNumber'])
                ? (string) $data['ProductionOrderOriginNumber']
                : null,
            'warehouse' => $data['Warehouse'] ?? null,
            'productionBillOfMaterial' => $data['ProductionBillOfMaterial'] ?? null,
            'project' => $data['Project'] ?? null,
            'remarks' => $data['Remarks'] ?? null,
            'customer' => $data['Customer'] ?? null,
            'priority' => $data['Priority'] ?? null,
            'startDate' => $data['StartDate'] ?? null,
            'closingDate' => $data['ClosingDate'] ?? null,
            'releaseDate' => $data['ReleaseDate'] ?? null,
            'productionOrderLines' => $lines,
        ];
    }

    public function toArray(): array
    {
        $result = array_filter([
            'AbsoluteEntry' => $this->absoluteEntry,
            'DocumentNumber' => $this->documentNumber,
            'Series' => $this->series,
            'ItemNo' => $this->itemNo,
            'ProductionOrderStatus' => $this->productionOrderStatus?->value,
            'ProductionOrderType' => $this->productionOrderType?->value,
            'PlannedQuantity' => $this->plannedQuantity,
            'CompletedQuantity' => $this->completedQuantity,
            'RejectedQuantity' => $this->rejectedQuantity,
            'PostingDate' => $this->postingDate,
            'DueDate' => $this->dueDate,
            'ProductionOrderOriginEntry' => $this->productionOrderOriginEntry,
            'ProductionOrderOriginNumber' => $this->productionOrderOriginNumber,
            'Warehouse' => $this->warehouse,
            'ProductionBillOfMaterial' => $this->productionBillOfMaterial,
            'Project' => $this->project,
            'Remarks' => $this->remarks,
            'Customer' => $this->customer,
            'Priority' => $this->priority,
            'StartDate' => $this->startDate,
            'ClosingDate' => $this->closingDate,
            'ReleaseDate' => $this->releaseDate,
        ], fn ($value) => $value !== null);

        if ($this->productionOrderLines !== null) {
            $result['ProductionOrderLines'] = array_map(
                fn (ProductionOrderLineDto $line) => $line->toArray(),
                $this->productionOrderLines
            );
        }

        return $result;
    }
}

<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Production;

use SapB1\Toolkit\DTOs\Base\BaseDto;
use SapB1\Toolkit\Enums\ProductTreeType;

/**
 * @phpstan-consistent-constructor
 */
final class ProductTreeDto extends BaseDto
{
    /**
     * @param  array<ProductTreeLineDto>|null  $productTreeLines
     */
    public function __construct(
        public readonly ?string $treeCode = null,
        public readonly ?ProductTreeType $treeType = null,
        public readonly ?float $quantity = null,
        public readonly ?string $warehouse = null,
        public readonly ?int $priceList = null,
        public readonly ?string $distributionRule = null,
        public readonly ?string $distributionRule2 = null,
        public readonly ?string $distributionRule3 = null,
        public readonly ?string $distributionRule4 = null,
        public readonly ?string $distributionRule5 = null,
        public readonly ?string $project = null,
        public readonly ?string $hideComponents = null,
        public readonly ?string $remarks = null,
        public readonly ?array $productTreeLines = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        $lines = null;
        if (isset($data['ProductTreeLines']) && is_array($data['ProductTreeLines'])) {
            $lines = array_map(
                fn (array $line) => ProductTreeLineDto::fromArray($line),
                $data['ProductTreeLines']
            );
        }

        return [
            'treeCode' => $data['TreeCode'] ?? null,
            'treeType' => isset($data['TreeType']) ? ProductTreeType::tryFrom($data['TreeType']) : null,
            'quantity' => isset($data['Quantity']) ? (float) $data['Quantity'] : null,
            'warehouse' => $data['Warehouse'] ?? null,
            'priceList' => $data['PriceList'] ?? null,
            'distributionRule' => $data['DistributionRule'] ?? null,
            'distributionRule2' => $data['DistributionRule2'] ?? null,
            'distributionRule3' => $data['DistributionRule3'] ?? null,
            'distributionRule4' => $data['DistributionRule4'] ?? null,
            'distributionRule5' => $data['DistributionRule5'] ?? null,
            'project' => $data['Project'] ?? null,
            'hideComponents' => $data['HideBOMComponentsInPrintout'] ?? null,
            'remarks' => $data['Remarks'] ?? null,
            'productTreeLines' => $lines,
        ];
    }

    public function toArray(): array
    {
        $result = array_filter([
            'TreeCode' => $this->treeCode,
            'TreeType' => $this->treeType?->value,
            'Quantity' => $this->quantity,
            'Warehouse' => $this->warehouse,
            'PriceList' => $this->priceList,
            'DistributionRule' => $this->distributionRule,
            'DistributionRule2' => $this->distributionRule2,
            'DistributionRule3' => $this->distributionRule3,
            'DistributionRule4' => $this->distributionRule4,
            'DistributionRule5' => $this->distributionRule5,
            'Project' => $this->project,
            'HideBOMComponentsInPrintout' => $this->hideComponents,
            'Remarks' => $this->remarks,
        ], fn ($value) => $value !== null);

        if ($this->productTreeLines !== null) {
            $result['ProductTreeLines'] = array_map(
                fn (ProductTreeLineDto $line) => $line->toArray(),
                $this->productTreeLines
            );
        }

        return $result;
    }
}

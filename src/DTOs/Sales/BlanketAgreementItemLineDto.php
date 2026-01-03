<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Sales;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * Blanket Agreement Item Line DTO.
 *
 * @phpstan-consistent-constructor
 */
final class BlanketAgreementItemLineDto extends BaseDto
{
    public function __construct(
        public readonly ?int $lineNo = null,
        public readonly ?string $itemNo = null,
        public readonly ?string $itemDescription = null,
        public readonly ?string $itemGroup = null,
        public readonly ?float $plannedQuantity = null,
        public readonly ?float $cumulativeQuantity = null,
        public readonly ?float $openQuantity = null,
        public readonly ?float $unitPrice = null,
        public readonly ?float $plannedAmount = null,
        public readonly ?float $cumulativeAmount = null,
        public readonly ?float $openAmount = null,
        public readonly ?string $freeText = null,
        public readonly ?string $warehouse = null,
        public readonly ?string $uoMEntry = null,
        public readonly ?string $uoMCode = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'lineNo' => $data['LineNo'] ?? null,
            'itemNo' => $data['ItemNo'] ?? null,
            'itemDescription' => $data['ItemDescription'] ?? null,
            'itemGroup' => $data['ItemGroup'] ?? null,
            'plannedQuantity' => $data['PlannedQuantity'] ?? null,
            'cumulativeQuantity' => $data['CumulativeQuantity'] ?? null,
            'openQuantity' => $data['OpenQuantity'] ?? null,
            'unitPrice' => $data['UnitPrice'] ?? null,
            'plannedAmount' => $data['PlannedAmount'] ?? null,
            'cumulativeAmount' => $data['CumulativeAmount'] ?? null,
            'openAmount' => $data['OpenAmount'] ?? null,
            'freeText' => $data['FreeText'] ?? null,
            'warehouse' => $data['Warehouse'] ?? null,
            'uoMEntry' => $data['UoMEntry'] ?? null,
            'uoMCode' => $data['UoMCode'] ?? null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = [];

        if ($this->lineNo !== null) {
            $data['LineNo'] = $this->lineNo;
        }

        if ($this->itemNo !== null) {
            $data['ItemNo'] = $this->itemNo;
        }

        if ($this->itemDescription !== null) {
            $data['ItemDescription'] = $this->itemDescription;
        }

        if ($this->itemGroup !== null) {
            $data['ItemGroup'] = $this->itemGroup;
        }

        if ($this->plannedQuantity !== null) {
            $data['PlannedQuantity'] = $this->plannedQuantity;
        }

        if ($this->cumulativeQuantity !== null) {
            $data['CumulativeQuantity'] = $this->cumulativeQuantity;
        }

        if ($this->openQuantity !== null) {
            $data['OpenQuantity'] = $this->openQuantity;
        }

        if ($this->unitPrice !== null) {
            $data['UnitPrice'] = $this->unitPrice;
        }

        if ($this->plannedAmount !== null) {
            $data['PlannedAmount'] = $this->plannedAmount;
        }

        if ($this->cumulativeAmount !== null) {
            $data['CumulativeAmount'] = $this->cumulativeAmount;
        }

        if ($this->openAmount !== null) {
            $data['OpenAmount'] = $this->openAmount;
        }

        if ($this->freeText !== null) {
            $data['FreeText'] = $this->freeText;
        }

        if ($this->warehouse !== null) {
            $data['Warehouse'] = $this->warehouse;
        }

        if ($this->uoMEntry !== null) {
            $data['UoMEntry'] = $this->uoMEntry;
        }

        if ($this->uoMCode !== null) {
            $data['UoMCode'] = $this->uoMCode;
        }

        return $data;
    }
}

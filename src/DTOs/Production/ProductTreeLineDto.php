<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Production;

use SapB1\Toolkit\DTOs\Base\BaseDto;
use SapB1\Toolkit\Enums\IssueMethod;

/**
 * @phpstan-consistent-constructor
 */
final class ProductTreeLineDto extends BaseDto
{
    public function __construct(
        public readonly ?string $itemCode = null,
        public readonly ?float $quantity = null,
        public readonly ?string $warehouse = null,
        public readonly ?int $priceList = null,
        public readonly ?IssueMethod $issueMethod = null,
        public readonly ?string $itemDescription = null,
        public readonly ?float $additionalQuantity = null,
        public readonly ?int $lineNumber = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'itemCode' => $data['ItemCode'] ?? null,
            'quantity' => isset($data['Quantity']) ? (float) $data['Quantity'] : null,
            'warehouse' => $data['Warehouse'] ?? null,
            'priceList' => $data['PriceList'] ?? null,
            'issueMethod' => isset($data['IssueMethod']) ? IssueMethod::tryFrom($data['IssueMethod']) : null,
            'itemDescription' => $data['ItemDescription'] ?? null,
            'additionalQuantity' => isset($data['AdditionalQuantity']) ? (float) $data['AdditionalQuantity'] : null,
            'lineNumber' => $data['LineNumber'] ?? null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'ItemCode' => $this->itemCode,
            'Quantity' => $this->quantity,
            'Warehouse' => $this->warehouse,
            'PriceList' => $this->priceList,
            'IssueMethod' => $this->issueMethod?->value,
            'ItemDescription' => $this->itemDescription,
            'AdditionalQuantity' => $this->additionalQuantity,
            'LineNumber' => $this->lineNumber,
        ], fn ($value) => $value !== null);
    }
}

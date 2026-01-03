<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Inventory;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class InventoryOpeningBalanceDto extends BaseDto
{
    /**
     * @param  array<InventoryOpeningBalanceLineDto>  $inventoryOpeningBalanceLines
     */
    public function __construct(
        public readonly ?int $docEntry = null,
        public readonly ?int $docNum = null,
        public readonly ?string $docDate = null,
        public readonly ?string $postingDate = null,
        public readonly ?string $remarks = null,
        public readonly ?string $reference1 = null,
        public readonly ?string $reference2 = null,
        public readonly ?int $series = null,
        public readonly ?string $priceSource = null,
        public readonly ?string $documentStatus = null,
        public readonly array $inventoryOpeningBalanceLines = [],
    ) {}

    protected static function mapFromArray(array $data): array
    {
        $lines = [];
        if (isset($data['InventoryOpeningBalanceLines']) && is_array($data['InventoryOpeningBalanceLines'])) {
            foreach ($data['InventoryOpeningBalanceLines'] as $line) {
                $lines[] = InventoryOpeningBalanceLineDto::fromArray($line);
            }
        }

        return [
            'docEntry' => isset($data['DocEntry']) ? (int) $data['DocEntry'] : null,
            'docNum' => isset($data['DocNum']) ? (int) $data['DocNum'] : null,
            'docDate' => $data['DocDate'] ?? null,
            'postingDate' => $data['PostingDate'] ?? null,
            'remarks' => $data['Remarks'] ?? null,
            'reference1' => $data['Reference1'] ?? null,
            'reference2' => $data['Reference2'] ?? null,
            'series' => isset($data['Series']) ? (int) $data['Series'] : null,
            'priceSource' => $data['PriceSource'] ?? null,
            'documentStatus' => $data['DocumentStatus'] ?? null,
            'inventoryOpeningBalanceLines' => $lines,
        ];
    }

    public function toArray(): array
    {
        $data = array_filter([
            'DocEntry' => $this->docEntry,
            'DocNum' => $this->docNum,
            'DocDate' => $this->docDate,
            'PostingDate' => $this->postingDate,
            'Remarks' => $this->remarks,
            'Reference1' => $this->reference1,
            'Reference2' => $this->reference2,
            'Series' => $this->series,
            'PriceSource' => $this->priceSource,
            'DocumentStatus' => $this->documentStatus,
        ], fn ($value) => $value !== null);

        if (! empty($this->inventoryOpeningBalanceLines)) {
            $data['InventoryOpeningBalanceLines'] = array_map(
                fn (InventoryOpeningBalanceLineDto $line) => $line->toArray(),
                $this->inventoryOpeningBalanceLines
            );
        }

        return $data;
    }
}

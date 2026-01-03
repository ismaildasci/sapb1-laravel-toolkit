<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Inventory;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class InventoryPostingDto extends BaseDto
{
    /**
     * @param  array<InventoryPostingLineDto>  $inventoryPostingLines
     */
    public function __construct(
        public readonly ?int $docEntry = null,
        public readonly ?int $docNum = null,
        public readonly ?string $docDate = null,
        public readonly ?string $countDate = null,
        public readonly ?string $countTime = null,
        public readonly ?string $reference1 = null,
        public readonly ?string $reference2 = null,
        public readonly ?string $remarks = null,
        public readonly ?int $series = null,
        public readonly ?string $priceSource = null,
        public readonly array $inventoryPostingLines = [],
    ) {}

    protected static function mapFromArray(array $data): array
    {
        $lines = [];
        if (isset($data['InventoryPostingLines']) && is_array($data['InventoryPostingLines'])) {
            foreach ($data['InventoryPostingLines'] as $line) {
                $lines[] = InventoryPostingLineDto::fromArray($line);
            }
        }

        return [
            'docEntry' => isset($data['DocEntry']) ? (int) $data['DocEntry'] : null,
            'docNum' => isset($data['DocNum']) ? (int) $data['DocNum'] : null,
            'docDate' => $data['DocDate'] ?? null,
            'countDate' => $data['CountDate'] ?? null,
            'countTime' => $data['CountTime'] ?? null,
            'reference1' => $data['Reference1'] ?? null,
            'reference2' => $data['Reference2'] ?? null,
            'remarks' => $data['Remarks'] ?? null,
            'series' => isset($data['Series']) ? (int) $data['Series'] : null,
            'priceSource' => $data['PriceSource'] ?? null,
            'inventoryPostingLines' => $lines,
        ];
    }

    public function toArray(): array
    {
        $data = array_filter([
            'DocEntry' => $this->docEntry,
            'DocNum' => $this->docNum,
            'DocDate' => $this->docDate,
            'CountDate' => $this->countDate,
            'CountTime' => $this->countTime,
            'Reference1' => $this->reference1,
            'Reference2' => $this->reference2,
            'Remarks' => $this->remarks,
            'Series' => $this->series,
            'PriceSource' => $this->priceSource,
        ], fn ($value) => $value !== null);

        if (! empty($this->inventoryPostingLines)) {
            $data['InventoryPostingLines'] = array_map(
                fn (InventoryPostingLineDto $line) => $line->toArray(),
                $this->inventoryPostingLines
            );
        }

        return $data;
    }
}

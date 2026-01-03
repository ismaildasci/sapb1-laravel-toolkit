<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Inventory;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class InventoryCountingDto extends BaseDto
{
    /**
     * @param  array<InventoryCountingLineDto>  $inventoryCountingLines
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
        public readonly ?string $countingType = null,
        public readonly ?string $documentStatus = null,
        public readonly array $inventoryCountingLines = [],
    ) {}

    protected static function mapFromArray(array $data): array
    {
        $lines = [];
        if (isset($data['InventoryCountingLines']) && is_array($data['InventoryCountingLines'])) {
            foreach ($data['InventoryCountingLines'] as $line) {
                $lines[] = InventoryCountingLineDto::fromArray($line);
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
            'countingType' => $data['CountingType'] ?? null,
            'documentStatus' => $data['DocumentStatus'] ?? null,
            'inventoryCountingLines' => $lines,
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
            'CountingType' => $this->countingType,
            'DocumentStatus' => $this->documentStatus,
        ], fn ($value) => $value !== null);

        if (! empty($this->inventoryCountingLines)) {
            $data['InventoryCountingLines'] = array_map(
                fn (InventoryCountingLineDto $line) => $line->toArray(),
                $this->inventoryCountingLines
            );
        }

        return $data;
    }
}

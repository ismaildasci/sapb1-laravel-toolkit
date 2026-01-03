<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Inventory;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class InventoryTransferRequestDto extends BaseDto
{
    /**
     * @param  array<InventoryTransferRequestLineDto>  $stockTransferLines
     */
    public function __construct(
        public readonly ?int $docEntry = null,
        public readonly ?int $docNum = null,
        public readonly ?string $docDate = null,
        public readonly ?string $dueDate = null,
        public readonly ?string $fromWarehouse = null,
        public readonly ?string $toWarehouse = null,
        public readonly ?string $comments = null,
        public readonly ?int $series = null,
        public readonly ?string $priceList = null,
        public readonly array $stockTransferLines = [],
    ) {}

    protected static function mapFromArray(array $data): array
    {
        $lines = [];
        if (isset($data['StockTransferLines']) && is_array($data['StockTransferLines'])) {
            foreach ($data['StockTransferLines'] as $line) {
                $lines[] = InventoryTransferRequestLineDto::fromArray($line);
            }
        }

        return [
            'docEntry' => isset($data['DocEntry']) ? (int) $data['DocEntry'] : null,
            'docNum' => isset($data['DocNum']) ? (int) $data['DocNum'] : null,
            'docDate' => $data['DocDate'] ?? null,
            'dueDate' => $data['DueDate'] ?? null,
            'fromWarehouse' => $data['FromWarehouse'] ?? null,
            'toWarehouse' => $data['ToWarehouse'] ?? null,
            'comments' => $data['Comments'] ?? null,
            'series' => isset($data['Series']) ? (int) $data['Series'] : null,
            'priceList' => isset($data['PriceList']) ? (string) $data['PriceList'] : null,
            'stockTransferLines' => $lines,
        ];
    }

    public function toArray(): array
    {
        $data = array_filter([
            'DocEntry' => $this->docEntry,
            'DocNum' => $this->docNum,
            'DocDate' => $this->docDate,
            'DueDate' => $this->dueDate,
            'FromWarehouse' => $this->fromWarehouse,
            'ToWarehouse' => $this->toWarehouse,
            'Comments' => $this->comments,
            'Series' => $this->series,
            'PriceList' => $this->priceList,
        ], fn ($value) => $value !== null);

        if (! empty($this->stockTransferLines)) {
            $data['StockTransferLines'] = array_map(
                fn (InventoryTransferRequestLineDto $line) => $line->toArray(),
                $this->stockTransferLines
            );
        }

        return $data;
    }
}

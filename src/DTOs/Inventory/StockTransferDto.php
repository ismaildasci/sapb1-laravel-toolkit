<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Inventory;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class StockTransferDto extends BaseDto
{
    /**
     * @param  array<StockTransferLineDto>  $stockTransferLines
     */
    public function __construct(
        public readonly ?int $docEntry = null,
        public readonly ?int $docNum = null,
        public readonly ?string $docDate = null,
        public readonly ?string $dueDate = null,
        public readonly ?string $taxDate = null,
        public readonly ?string $cardCode = null,
        public readonly ?string $cardName = null,
        public readonly ?string $fromWarehouse = null,
        public readonly ?string $toWarehouse = null,
        public readonly ?string $comments = null,
        public readonly ?string $journalMemo = null,
        public readonly ?string $salesPersonCode = null,
        public readonly ?string $priceList = null,
        public readonly array $stockTransferLines = [],
    ) {}

    protected static function mapFromArray(array $data): array
    {
        $lines = [];
        if (isset($data['StockTransferLines']) && is_array($data['StockTransferLines'])) {
            foreach ($data['StockTransferLines'] as $line) {
                $lines[] = StockTransferLineDto::fromArray($line);
            }
        }

        return [
            'docEntry' => isset($data['DocEntry']) ? (int) $data['DocEntry'] : null,
            'docNum' => isset($data['DocNum']) ? (int) $data['DocNum'] : null,
            'docDate' => $data['DocDate'] ?? null,
            'dueDate' => $data['DueDate'] ?? null,
            'taxDate' => $data['TaxDate'] ?? null,
            'cardCode' => $data['CardCode'] ?? null,
            'cardName' => $data['CardName'] ?? null,
            'fromWarehouse' => $data['FromWarehouse'] ?? null,
            'toWarehouse' => $data['ToWarehouse'] ?? null,
            'comments' => $data['Comments'] ?? null,
            'journalMemo' => $data['JournalMemo'] ?? null,
            'salesPersonCode' => isset($data['SalesPersonCode']) ? (string) $data['SalesPersonCode'] : null,
            'priceList' => isset($data['PriceList']) ? (string) $data['PriceList'] : null,
            'stockTransferLines' => $lines,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = array_filter([
            'DocEntry' => $this->docEntry,
            'DocNum' => $this->docNum,
            'DocDate' => $this->docDate,
            'DueDate' => $this->dueDate,
            'TaxDate' => $this->taxDate,
            'CardCode' => $this->cardCode,
            'CardName' => $this->cardName,
            'FromWarehouse' => $this->fromWarehouse,
            'ToWarehouse' => $this->toWarehouse,
            'Comments' => $this->comments,
            'JournalMemo' => $this->journalMemo,
            'SalesPersonCode' => $this->salesPersonCode,
            'PriceList' => $this->priceList,
        ], fn ($value) => $value !== null);

        if (! empty($this->stockTransferLines)) {
            $data['StockTransferLines'] = array_map(
                fn (StockTransferLineDto $line) => $line->toArray(),
                $this->stockTransferLines
            );
        }

        return $data;
    }
}

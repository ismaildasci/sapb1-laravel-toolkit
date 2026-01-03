<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Inventory;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * Inventory General Entry DTO (Goods Receipt).
 *
 * @phpstan-consistent-constructor
 */
final class InventoryGenEntryDto extends BaseDto
{
    /**
     * @param  array<InventoryGenEntryLineDto>  $documentLines
     */
    public function __construct(
        public readonly ?int $docEntry = null,
        public readonly ?int $docNum = null,
        public readonly ?string $docDate = null,
        public readonly ?string $dueDate = null,
        public readonly ?string $taxDate = null,
        public readonly ?string $reference1 = null,
        public readonly ?string $reference2 = null,
        public readonly ?string $comments = null,
        public readonly ?string $journalMemo = null,
        public readonly ?float $docTotal = null,
        public readonly ?float $docTotalFc = null,
        public readonly ?string $docCurrency = null,
        public readonly ?float $docRate = null,
        public readonly ?int $series = null,
        public readonly ?string $priceList = null,
        public readonly array $documentLines = [],
    ) {}

    protected static function mapFromArray(array $data): array
    {
        $lines = [];
        if (isset($data['DocumentLines']) && is_array($data['DocumentLines'])) {
            foreach ($data['DocumentLines'] as $line) {
                $lines[] = InventoryGenEntryLineDto::fromArray($line);
            }
        }

        return [
            'docEntry' => isset($data['DocEntry']) ? (int) $data['DocEntry'] : null,
            'docNum' => isset($data['DocNum']) ? (int) $data['DocNum'] : null,
            'docDate' => $data['DocDate'] ?? null,
            'dueDate' => $data['DueDate'] ?? null,
            'taxDate' => $data['TaxDate'] ?? null,
            'reference1' => $data['Reference1'] ?? null,
            'reference2' => $data['Reference2'] ?? null,
            'comments' => $data['Comments'] ?? null,
            'journalMemo' => $data['JournalMemo'] ?? null,
            'docTotal' => isset($data['DocTotal']) ? (float) $data['DocTotal'] : null,
            'docTotalFc' => isset($data['DocTotalFC']) ? (float) $data['DocTotalFC'] : null,
            'docCurrency' => $data['DocCurrency'] ?? null,
            'docRate' => isset($data['DocRate']) ? (float) $data['DocRate'] : null,
            'series' => isset($data['Series']) ? (int) $data['Series'] : null,
            'priceList' => isset($data['PriceList']) ? (string) $data['PriceList'] : null,
            'documentLines' => $lines,
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
            'Reference1' => $this->reference1,
            'Reference2' => $this->reference2,
            'Comments' => $this->comments,
            'JournalMemo' => $this->journalMemo,
            'DocTotal' => $this->docTotal,
            'DocTotalFC' => $this->docTotalFc,
            'DocCurrency' => $this->docCurrency,
            'DocRate' => $this->docRate,
            'Series' => $this->series,
            'PriceList' => $this->priceList,
        ], fn ($value) => $value !== null);

        if (! empty($this->documentLines)) {
            $data['DocumentLines'] = array_map(
                fn (InventoryGenEntryLineDto $line) => $line->toArray(),
                $this->documentLines
            );
        }

        return $data;
    }
}

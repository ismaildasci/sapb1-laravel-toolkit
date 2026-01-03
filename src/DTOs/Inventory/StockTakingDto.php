<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Inventory;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class StockTakingDto extends BaseDto
{
    /**
     * @param  array<StockTakingLineDto>  $stockTakingLines
     */
    public function __construct(
        public readonly ?int $docEntry = null,
        public readonly ?int $docNum = null,
        public readonly ?string $stockTakingDate = null,
        public readonly ?string $remarks = null,
        public readonly ?string $reference1 = null,
        public readonly ?string $reference2 = null,
        public readonly ?int $series = null,
        public readonly ?string $documentStatus = null,
        public readonly array $stockTakingLines = [],
    ) {}

    protected static function mapFromArray(array $data): array
    {
        $lines = [];
        if (isset($data['StockTakingLines']) && is_array($data['StockTakingLines'])) {
            foreach ($data['StockTakingLines'] as $line) {
                $lines[] = StockTakingLineDto::fromArray($line);
            }
        }

        return [
            'docEntry' => isset($data['DocEntry']) ? (int) $data['DocEntry'] : null,
            'docNum' => isset($data['DocNum']) ? (int) $data['DocNum'] : null,
            'stockTakingDate' => $data['StockTakingDate'] ?? null,
            'remarks' => $data['Remarks'] ?? null,
            'reference1' => $data['Reference1'] ?? null,
            'reference2' => $data['Reference2'] ?? null,
            'series' => isset($data['Series']) ? (int) $data['Series'] : null,
            'documentStatus' => $data['DocumentStatus'] ?? null,
            'stockTakingLines' => $lines,
        ];
    }

    public function toArray(): array
    {
        $data = array_filter([
            'DocEntry' => $this->docEntry,
            'DocNum' => $this->docNum,
            'StockTakingDate' => $this->stockTakingDate,
            'Remarks' => $this->remarks,
            'Reference1' => $this->reference1,
            'Reference2' => $this->reference2,
            'Series' => $this->series,
            'DocumentStatus' => $this->documentStatus,
        ], fn ($value) => $value !== null);

        if (! empty($this->stockTakingLines)) {
            $data['StockTakingLines'] = array_map(fn (StockTakingLineDto $line) => $line->toArray(), $this->stockTakingLines);
        }

        return $data;
    }
}

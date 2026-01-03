<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Finance;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class JournalEntryDto extends BaseDto
{
    /**
     * @param  array<JournalEntryLineDto>  $journalEntryLines
     */
    public function __construct(
        public readonly ?int $jdtNum = null,
        public readonly ?string $refDate = null,
        public readonly ?string $dueDate = null,
        public readonly ?string $taxDate = null,
        public readonly ?string $memo = null,
        public readonly ?string $reference = null,
        public readonly ?string $reference2 = null,
        public readonly ?string $transactionCode = null,
        public readonly ?string $projectCode = null,
        public readonly ?string $automaticWT = null,
        public readonly ?string $stornoDate = null,
        public readonly ?string $vatDate = null,
        public readonly ?int $series = null,
        public readonly ?int $stampTax = null,
        public readonly ?int $docSeries = null,
        public readonly ?string $indicator = null,
        public readonly ?string $blockDunningLetter = null,
        public readonly array $journalEntryLines = [],
    ) {}

    protected static function mapFromArray(array $data): array
    {
        $lines = [];
        if (isset($data['JournalEntryLines']) && is_array($data['JournalEntryLines'])) {
            foreach ($data['JournalEntryLines'] as $line) {
                $lines[] = JournalEntryLineDto::fromArray($line);
            }
        }

        return [
            'jdtNum' => isset($data['JdtNum']) ? (int) $data['JdtNum'] : null,
            'refDate' => $data['ReferenceDate'] ?? null,
            'dueDate' => $data['DueDate'] ?? null,
            'taxDate' => $data['TaxDate'] ?? null,
            'memo' => $data['Memo'] ?? null,
            'reference' => $data['Reference'] ?? null,
            'reference2' => $data['Reference2'] ?? null,
            'transactionCode' => $data['TransactionCode'] ?? null,
            'projectCode' => $data['ProjectCode'] ?? null,
            'automaticWT' => $data['AutomaticWT'] ?? null,
            'stornoDate' => $data['StornoDate'] ?? null,
            'vatDate' => $data['VatDate'] ?? null,
            'series' => isset($data['Series']) ? (int) $data['Series'] : null,
            'stampTax' => isset($data['StampTax']) ? (int) $data['StampTax'] : null,
            'docSeries' => isset($data['DocSeries']) ? (int) $data['DocSeries'] : null,
            'indicator' => $data['Indicator'] ?? null,
            'blockDunningLetter' => $data['BlockDunningLetter'] ?? null,
            'journalEntryLines' => $lines,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = array_filter([
            'JdtNum' => $this->jdtNum,
            'ReferenceDate' => $this->refDate,
            'DueDate' => $this->dueDate,
            'TaxDate' => $this->taxDate,
            'Memo' => $this->memo,
            'Reference' => $this->reference,
            'Reference2' => $this->reference2,
            'TransactionCode' => $this->transactionCode,
            'ProjectCode' => $this->projectCode,
            'AutomaticWT' => $this->automaticWT,
            'StornoDate' => $this->stornoDate,
            'VatDate' => $this->vatDate,
            'Series' => $this->series,
            'StampTax' => $this->stampTax,
            'DocSeries' => $this->docSeries,
            'Indicator' => $this->indicator,
            'BlockDunningLetter' => $this->blockDunningLetter,
        ], fn ($value) => $value !== null);

        if (! empty($this->journalEntryLines)) {
            $data['JournalEntryLines'] = array_map(
                fn (JournalEntryLineDto $line) => $line->toArray(),
                $this->journalEntryLines
            );
        }

        return $data;
    }
}

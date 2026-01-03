<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Finance;

use SapB1\Toolkit\Builders\BaseBuilder;
use SapB1\Toolkit\DTOs\Finance\JournalEntryLineDto;

/**
 * Builder for Journal Entries.
 *
 * @phpstan-consistent-constructor
 */
final class JournalEntryBuilder extends BaseBuilder
{
    public function referenceDate(string $date): static
    {
        return $this->set('ReferenceDate', $date);
    }

    public function dueDate(string $date): static
    {
        return $this->set('DueDate', $date);
    }

    public function taxDate(string $date): static
    {
        return $this->set('TaxDate', $date);
    }

    public function memo(string $memo): static
    {
        return $this->set('Memo', $memo);
    }

    public function reference(string $reference): static
    {
        return $this->set('Reference', $reference);
    }

    public function reference2(string $reference): static
    {
        return $this->set('Reference2', $reference);
    }

    public function transactionCode(string $code): static
    {
        return $this->set('TransactionCode', $code);
    }

    public function projectCode(string $code): static
    {
        return $this->set('ProjectCode', $code);
    }

    public function series(int $series): static
    {
        return $this->set('Series', $series);
    }

    public function indicator(string $indicator): static
    {
        return $this->set('Indicator', $indicator);
    }

    /**
     * @param  array<JournalEntryLineDto|array<string, mixed>>  $lines
     */
    public function journalEntryLines(array $lines): static
    {
        $mappedLines = array_map(
            fn ($line) => $line instanceof JournalEntryLineDto ? $line->toArray() : $line,
            $lines
        );

        return $this->set('JournalEntryLines', $mappedLines);
    }

    /**
     * @param  JournalEntryLineDto|array<string, mixed>  $line
     */
    public function addLine(JournalEntryLineDto|array $line): static
    {
        $lines = $this->get('JournalEntryLines', []);
        $lines[] = $line instanceof JournalEntryLineDto ? $line->toArray() : $line;

        return $this->set('JournalEntryLines', $lines);
    }

    /**
     * Add a debit line.
     */
    public function debit(string $accountCode, float $amount, ?string $memo = null): static
    {
        $line = [
            'AccountCode' => $accountCode,
            'Debit' => $amount,
        ];

        if ($memo !== null) {
            $line['LineMemo'] = $memo;
        }

        return $this->addLine($line);
    }

    /**
     * Add a credit line.
     */
    public function credit(string $accountCode, float $amount, ?string $memo = null): static
    {
        $line = [
            'AccountCode' => $accountCode,
            'Credit' => $amount,
        ];

        if ($memo !== null) {
            $line['LineMemo'] = $memo;
        }

        return $this->addLine($line);
    }

    /**
     * @return array<string, mixed>
     */
    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

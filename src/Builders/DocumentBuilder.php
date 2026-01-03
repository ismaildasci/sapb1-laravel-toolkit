<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders;

use SapB1\Toolkit\DTOs\DocumentLineDto;

/**
 * Base builder for SAP B1 documents.
 *
 * @phpstan-consistent-constructor
 */
abstract class DocumentBuilder extends BaseBuilder
{
    public function cardCode(string $cardCode): static
    {
        return $this->set('CardCode', $cardCode);
    }

    public function cardName(string $cardName): static
    {
        return $this->set('CardName', $cardName);
    }

    public function docDate(string $date): static
    {
        return $this->set('DocDate', $date);
    }

    public function docDueDate(string $date): static
    {
        return $this->set('DocDueDate', $date);
    }

    public function taxDate(string $date): static
    {
        return $this->set('TaxDate', $date);
    }

    public function docCurrency(string $currency): static
    {
        return $this->set('DocCurrency', $currency);
    }

    public function docRate(float $rate): static
    {
        return $this->set('DocRate', $rate);
    }

    public function numAtCard(string $numAtCard): static
    {
        return $this->set('NumAtCard', $numAtCard);
    }

    public function comments(string $comments): static
    {
        return $this->set('Comments', $comments);
    }

    public function payToCode(string $code): static
    {
        return $this->set('PayToCode', $code);
    }

    public function shipToCode(string $code): static
    {
        return $this->set('ShipToCode', $code);
    }

    public function salesPersonCode(int $code): static
    {
        return $this->set('SalesPersonCode', $code);
    }

    public function contactPersonCode(int $code): static
    {
        return $this->set('ContactPersonCode', $code);
    }

    public function series(int $series): static
    {
        return $this->set('Series', $series);
    }

    public function indicator(string $indicator): static
    {
        return $this->set('Indicator', $indicator);
    }

    public function project(string $project): static
    {
        return $this->set('Project', $project);
    }

    public function discountPercent(float $percent): static
    {
        return $this->set('DiscountPercent', $percent);
    }

    /**
     * @param  array<DocumentLineDto|array<string, mixed>>  $lines
     */
    public function documentLines(array $lines): static
    {
        $mappedLines = array_map(
            fn ($line) => $line instanceof DocumentLineDto ? $line->toArray() : $line,
            $lines
        );

        return $this->set('DocumentLines', $mappedLines);
    }

    /**
     * @param  DocumentLineDto|array<string, mixed>  $line
     */
    public function addLine(DocumentLineDto|array $line): static
    {
        $lines = $this->get('DocumentLines', []);
        $lines[] = $line instanceof DocumentLineDto ? $line->toArray() : $line;

        return $this->set('DocumentLines', $lines);
    }

    /**
     * @return array<string, mixed>
     */
    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs;

use SapB1\Toolkit\DTOs\Base\BaseDto;
use SapB1\Toolkit\Enums\DocumentStatus;

/**
 * @phpstan-consistent-constructor
 */
class DocumentDto extends BaseDto
{
    /**
     * @param  array<DocumentLineDto>  $documentLines
     */
    public function __construct(
        public readonly ?int $docEntry = null,
        public readonly ?int $docNum = null,
        public readonly ?string $cardCode = null,
        public readonly ?string $cardName = null,
        public readonly ?string $docDate = null,
        public readonly ?string $docDueDate = null,
        public readonly ?string $taxDate = null,
        public readonly ?string $docCurrency = null,
        public readonly ?float $docRate = null,
        public readonly ?float $docTotal = null,
        public readonly ?float $docTotalFc = null,
        public readonly ?float $vatSum = null,
        public readonly ?float $vatSumFc = null,
        public readonly ?float $discountPercent = null,
        public readonly ?float $totalDiscount = null,
        public readonly ?string $numAtCard = null,
        public readonly ?string $comments = null,
        public readonly ?DocumentStatus $documentStatus = null,
        public readonly ?string $payToCode = null,
        public readonly ?string $shipToCode = null,
        public readonly ?string $salesPersonCode = null,
        public readonly ?string $contactPersonCode = null,
        public readonly ?string $series = null,
        public readonly ?string $indicator = null,
        public readonly ?string $federalTaxId = null,
        public readonly ?string $project = null,
        public readonly array $documentLines = [],
    ) {}

    protected static function mapFromArray(array $data): array
    {
        $lines = [];
        if (isset($data['DocumentLines']) && is_array($data['DocumentLines'])) {
            foreach ($data['DocumentLines'] as $line) {
                $lines[] = DocumentLineDto::fromArray($line);
            }
        }

        return [
            'docEntry' => isset($data['DocEntry']) ? (int) $data['DocEntry'] : null,
            'docNum' => isset($data['DocNum']) ? (int) $data['DocNum'] : null,
            'cardCode' => $data['CardCode'] ?? null,
            'cardName' => $data['CardName'] ?? null,
            'docDate' => $data['DocDate'] ?? null,
            'docDueDate' => $data['DocDueDate'] ?? null,
            'taxDate' => $data['TaxDate'] ?? null,
            'docCurrency' => $data['DocCurrency'] ?? null,
            'docRate' => isset($data['DocRate']) ? (float) $data['DocRate'] : null,
            'docTotal' => isset($data['DocTotal']) ? (float) $data['DocTotal'] : null,
            'docTotalFc' => isset($data['DocTotalFc']) ? (float) $data['DocTotalFc'] : null,
            'vatSum' => isset($data['VatSum']) ? (float) $data['VatSum'] : null,
            'vatSumFc' => isset($data['VatSumFc']) ? (float) $data['VatSumFc'] : null,
            'discountPercent' => isset($data['DiscountPercent']) ? (float) $data['DiscountPercent'] : null,
            'totalDiscount' => isset($data['TotalDiscount']) ? (float) $data['TotalDiscount'] : null,
            'numAtCard' => $data['NumAtCard'] ?? null,
            'comments' => $data['Comments'] ?? null,
            'documentStatus' => isset($data['DocumentStatus']) ? DocumentStatus::tryFrom($data['DocumentStatus']) : null,
            'payToCode' => $data['PayToCode'] ?? null,
            'shipToCode' => $data['ShipToCode'] ?? null,
            'salesPersonCode' => isset($data['SalesPersonCode']) ? (string) $data['SalesPersonCode'] : null,
            'contactPersonCode' => isset($data['ContactPersonCode']) ? (string) $data['ContactPersonCode'] : null,
            'series' => isset($data['Series']) ? (string) $data['Series'] : null,
            'indicator' => $data['Indicator'] ?? null,
            'federalTaxId' => $data['FederalTaxID'] ?? null,
            'project' => $data['Project'] ?? null,
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
            'CardCode' => $this->cardCode,
            'CardName' => $this->cardName,
            'DocDate' => $this->docDate,
            'DocDueDate' => $this->docDueDate,
            'TaxDate' => $this->taxDate,
            'DocCurrency' => $this->docCurrency,
            'DocRate' => $this->docRate,
            'DocTotal' => $this->docTotal,
            'DocTotalFc' => $this->docTotalFc,
            'VatSum' => $this->vatSum,
            'VatSumFc' => $this->vatSumFc,
            'DiscountPercent' => $this->discountPercent,
            'TotalDiscount' => $this->totalDiscount,
            'NumAtCard' => $this->numAtCard,
            'Comments' => $this->comments,
            'DocumentStatus' => $this->documentStatus?->value,
            'PayToCode' => $this->payToCode,
            'ShipToCode' => $this->shipToCode,
            'SalesPersonCode' => $this->salesPersonCode,
            'ContactPersonCode' => $this->contactPersonCode,
            'Series' => $this->series,
            'Indicator' => $this->indicator,
            'FederalTaxID' => $this->federalTaxId,
            'Project' => $this->project,
        ], fn ($value) => $value !== null);

        if (! empty($this->documentLines)) {
            $data['DocumentLines'] = array_map(
                fn (DocumentLineDto $line) => $line->toArray(),
                $this->documentLines
            );
        }

        return $data;
    }
}

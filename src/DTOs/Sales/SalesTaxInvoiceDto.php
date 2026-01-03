<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Sales;

use SapB1\Toolkit\DTOs\DocumentDto;
use SapB1\Toolkit\Enums\TaxInvoiceType;

/**
 * Sales Tax Invoice DTO.
 *
 * @phpstan-consistent-constructor
 */
final class SalesTaxInvoiceDto extends DocumentDto
{
    public function __construct(
        ?int $docEntry = null,
        ?int $docNum = null,
        ?string $cardCode = null,
        ?string $cardName = null,
        ?string $docDate = null,
        ?string $docDueDate = null,
        ?string $taxDate = null,
        ?string $docCurrency = null,
        ?float $docRate = null,
        ?float $docTotal = null,
        ?float $docTotalFc = null,
        ?float $vatSum = null,
        ?float $vatSumFc = null,
        ?float $discountPercent = null,
        ?float $totalDiscount = null,
        ?string $numAtCard = null,
        ?string $comments = null,
        ?\SapB1\Toolkit\Enums\DocumentStatus $documentStatus = null,
        ?string $payToCode = null,
        ?string $shipToCode = null,
        ?string $salesPersonCode = null,
        ?string $contactPersonCode = null,
        ?string $series = null,
        ?string $indicator = null,
        ?string $federalTaxId = null,
        ?string $project = null,
        array $documentLines = [],
        public readonly ?TaxInvoiceType $taxInvoiceType = null,
    ) {
        parent::__construct(
            docEntry: $docEntry,
            docNum: $docNum,
            cardCode: $cardCode,
            cardName: $cardName,
            docDate: $docDate,
            docDueDate: $docDueDate,
            taxDate: $taxDate,
            docCurrency: $docCurrency,
            docRate: $docRate,
            docTotal: $docTotal,
            docTotalFc: $docTotalFc,
            vatSum: $vatSum,
            vatSumFc: $vatSumFc,
            discountPercent: $discountPercent,
            totalDiscount: $totalDiscount,
            numAtCard: $numAtCard,
            comments: $comments,
            documentStatus: $documentStatus,
            payToCode: $payToCode,
            shipToCode: $shipToCode,
            salesPersonCode: $salesPersonCode,
            contactPersonCode: $contactPersonCode,
            series: $series,
            indicator: $indicator,
            federalTaxId: $federalTaxId,
            project: $project,
            documentLines: $documentLines,
        );
    }

    protected static function mapFromArray(array $data): array
    {
        return array_merge(parent::mapFromArray($data), [
            'taxInvoiceType' => isset($data['TaxInvoiceType']) ? TaxInvoiceType::tryFrom($data['TaxInvoiceType']) : null,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = parent::toArray();

        if ($this->taxInvoiceType !== null) {
            $data['TaxInvoiceType'] = $this->taxInvoiceType->value;
        }

        return $data;
    }
}

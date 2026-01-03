<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Sales;

use SapB1\Toolkit\DTOs\DocumentDto;
use SapB1\Toolkit\Enums\DownPaymentStatus;
use SapB1\Toolkit\Enums\DownPaymentType;

/**
 * Sales Down Payment Request DTO.
 *
 * @phpstan-consistent-constructor
 */
final class DownPaymentDto extends DocumentDto
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
        public readonly ?DownPaymentType $downPaymentType = null,
        public readonly ?DownPaymentStatus $downPaymentStatus = null,
        public readonly ?float $downPaymentAmount = null,
        public readonly ?float $downPaymentAmountFc = null,
        public readonly ?float $downPaymentPercentage = null,
        public readonly ?int $downPaymentToDraw = null,
        public readonly ?float $downPaymentToDrawFc = null,
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
            'downPaymentType' => isset($data['DownPaymentType']) ? DownPaymentType::tryFrom($data['DownPaymentType']) : null,
            'downPaymentStatus' => isset($data['DownPaymentStatus']) ? DownPaymentStatus::tryFrom($data['DownPaymentStatus']) : null,
            'downPaymentAmount' => isset($data['DownPaymentAmount']) ? (float) $data['DownPaymentAmount'] : null,
            'downPaymentAmountFc' => isset($data['DownPaymentAmountFC']) ? (float) $data['DownPaymentAmountFC'] : null,
            'downPaymentPercentage' => isset($data['DownPaymentPercentage']) ? (float) $data['DownPaymentPercentage'] : null,
            'downPaymentToDraw' => isset($data['DownPaymentToDraw']) ? (int) $data['DownPaymentToDraw'] : null,
            'downPaymentToDrawFc' => isset($data['DownPaymentToDrawFC']) ? (float) $data['DownPaymentToDrawFC'] : null,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = parent::toArray();

        if ($this->downPaymentType !== null) {
            $data['DownPaymentType'] = $this->downPaymentType->value;
        }

        if ($this->downPaymentStatus !== null) {
            $data['DownPaymentStatus'] = $this->downPaymentStatus->value;
        }

        if ($this->downPaymentAmount !== null) {
            $data['DownPaymentAmount'] = $this->downPaymentAmount;
        }

        if ($this->downPaymentAmountFc !== null) {
            $data['DownPaymentAmountFC'] = $this->downPaymentAmountFc;
        }

        if ($this->downPaymentPercentage !== null) {
            $data['DownPaymentPercentage'] = $this->downPaymentPercentage;
        }

        if ($this->downPaymentToDraw !== null) {
            $data['DownPaymentToDraw'] = $this->downPaymentToDraw;
        }

        if ($this->downPaymentToDrawFc !== null) {
            $data['DownPaymentToDrawFC'] = $this->downPaymentToDrawFc;
        }

        return $data;
    }
}

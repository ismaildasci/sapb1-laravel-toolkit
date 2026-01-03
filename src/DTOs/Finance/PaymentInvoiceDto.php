<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Finance;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class PaymentInvoiceDto extends BaseDto
{
    public function __construct(
        public readonly ?int $lineNum = null,
        public readonly ?int $docEntry = null,
        public readonly ?float $sumApplied = null,
        public readonly ?float $sumAppliedFc = null,
        public readonly ?float $sumAppliedSys = null,
        public readonly ?int $docLine = null,
        public readonly ?string $docType = null,
        public readonly ?float $discountPercent = null,
        public readonly ?float $totalDiscount = null,
        public readonly ?float $totalDiscountFc = null,
        public readonly ?int $invoiceType = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'lineNum' => isset($data['LineNum']) ? (int) $data['LineNum'] : null,
            'docEntry' => isset($data['DocEntry']) ? (int) $data['DocEntry'] : null,
            'sumApplied' => isset($data['SumApplied']) ? (float) $data['SumApplied'] : null,
            'sumAppliedFc' => isset($data['SumAppliedFC']) ? (float) $data['SumAppliedFC'] : null,
            'sumAppliedSys' => isset($data['SumAppliedSys']) ? (float) $data['SumAppliedSys'] : null,
            'docLine' => isset($data['DocLine']) ? (int) $data['DocLine'] : null,
            'docType' => $data['DocType'] ?? null,
            'discountPercent' => isset($data['DiscountPercent']) ? (float) $data['DiscountPercent'] : null,
            'totalDiscount' => isset($data['TotalDiscount']) ? (float) $data['TotalDiscount'] : null,
            'totalDiscountFc' => isset($data['TotalDiscountFC']) ? (float) $data['TotalDiscountFC'] : null,
            'invoiceType' => isset($data['InvoiceType']) ? (int) $data['InvoiceType'] : null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'LineNum' => $this->lineNum,
            'DocEntry' => $this->docEntry,
            'SumApplied' => $this->sumApplied,
            'SumAppliedFC' => $this->sumAppliedFc,
            'SumAppliedSys' => $this->sumAppliedSys,
            'DocLine' => $this->docLine,
            'DocType' => $this->docType,
            'DiscountPercent' => $this->discountPercent,
            'TotalDiscount' => $this->totalDiscount,
            'TotalDiscountFC' => $this->totalDiscountFc,
            'InvoiceType' => $this->invoiceType,
        ], fn ($value) => $value !== null);
    }
}

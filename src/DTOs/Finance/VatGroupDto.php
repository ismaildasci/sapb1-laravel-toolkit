<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Finance;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class VatGroupDto extends BaseDto
{
    public function __construct(
        public readonly ?string $code = null,
        public readonly ?string $name = null,
        public readonly ?string $category = null,
        public readonly ?float $taxAccount = null,
        public readonly ?string $euPurchaseAccount = null,
        public readonly ?string $euSalesAccount = null,
        public readonly ?string $inactive = null,
        public readonly ?float $vatPercent = null,
        public readonly ?string $reportingCode = null,
        public readonly ?string $nonDeductiblePercent = null,
        public readonly ?string $isDefault = null,
        public readonly ?string $acquisitionReversed = null,
        public readonly ?string $nonDeductibleAcqTax = null,
        public readonly ?string $deferredTaxAcc = null,
        public readonly ?string $vATServiceLiabilityCode = null,
        public readonly ?string $vATRevenueAccount = null,
        public readonly ?string $isDownPayment = null,
        public readonly ?string $cashDiscountAccount = null,
        public readonly ?string $cashDiscountLiabilityAccount = null,
        public readonly ?string $taxReportInternalCode = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'code' => $data['Code'] ?? null,
            'name' => $data['Name'] ?? null,
            'category' => $data['Category'] ?? null,
            'taxAccount' => isset($data['TaxAccount']) ? (float) $data['TaxAccount'] : null,
            'euPurchaseAccount' => $data['EUPurchaseAccount'] ?? null,
            'euSalesAccount' => $data['EUSalesAccount'] ?? null,
            'inactive' => $data['Inactive'] ?? null,
            'vatPercent' => isset($data['VatPercent']) ? (float) $data['VatPercent'] : null,
            'reportingCode' => $data['ReportingCode'] ?? null,
            'nonDeductiblePercent' => $data['NonDeductiblePercent'] ?? null,
            'isDefault' => $data['IsDefault'] ?? null,
            'acquisitionReversed' => $data['AcquisitionReversed'] ?? null,
            'nonDeductibleAcqTax' => $data['NonDeductibleAcqTax'] ?? null,
            'deferredTaxAcc' => $data['DeferredTaxAcc'] ?? null,
            'vATServiceLiabilityCode' => $data['VATServiceLiabilityCode'] ?? null,
            'vATRevenueAccount' => $data['VATRevenueAccount'] ?? null,
            'isDownPayment' => $data['IsDownPayment'] ?? null,
            'cashDiscountAccount' => $data['CashDiscountAccount'] ?? null,
            'cashDiscountLiabilityAccount' => $data['CashDiscountLiabilityAccount'] ?? null,
            'taxReportInternalCode' => $data['TaxReportInternalCode'] ?? null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'Code' => $this->code,
            'Name' => $this->name,
            'Category' => $this->category,
            'TaxAccount' => $this->taxAccount,
            'EUPurchaseAccount' => $this->euPurchaseAccount,
            'EUSalesAccount' => $this->euSalesAccount,
            'Inactive' => $this->inactive,
            'VatPercent' => $this->vatPercent,
            'ReportingCode' => $this->reportingCode,
            'NonDeductiblePercent' => $this->nonDeductiblePercent,
            'IsDefault' => $this->isDefault,
            'AcquisitionReversed' => $this->acquisitionReversed,
            'NonDeductibleAcqTax' => $this->nonDeductibleAcqTax,
            'DeferredTaxAcc' => $this->deferredTaxAcc,
            'VATServiceLiabilityCode' => $this->vATServiceLiabilityCode,
            'VATRevenueAccount' => $this->vATRevenueAccount,
            'IsDownPayment' => $this->isDownPayment,
            'CashDiscountAccount' => $this->cashDiscountAccount,
            'CashDiscountLiabilityAccount' => $this->cashDiscountLiabilityAccount,
            'TaxReportInternalCode' => $this->taxReportInternalCode,
        ], fn ($value) => $value !== null);
    }
}

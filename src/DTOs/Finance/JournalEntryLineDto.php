<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Finance;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class JournalEntryLineDto extends BaseDto
{
    public function __construct(
        public readonly ?int $lineId = null,
        public readonly ?string $accountCode = null,
        public readonly ?float $debit = null,
        public readonly ?float $credit = null,
        public readonly ?float $fcDebit = null,
        public readonly ?float $fcCredit = null,
        public readonly ?string $fcCurrency = null,
        public readonly ?string $dueDate = null,
        public readonly ?string $shortName = null,
        public readonly ?string $contraAccount = null,
        public readonly ?string $lineMemo = null,
        public readonly ?string $referenceDate1 = null,
        public readonly ?string $referenceDate2 = null,
        public readonly ?string $reference1 = null,
        public readonly ?string $reference2 = null,
        public readonly ?string $costingCode = null,
        public readonly ?string $costingCode2 = null,
        public readonly ?string $costingCode3 = null,
        public readonly ?string $costingCode4 = null,
        public readonly ?string $costingCode5 = null,
        public readonly ?string $projectCode = null,
        public readonly ?string $taxDate = null,
        public readonly ?string $baseSum = null,
        public readonly ?string $taxGroup = null,
        public readonly ?string $taxCode = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'lineId' => isset($data['Line_ID']) ? (int) $data['Line_ID'] : null,
            'accountCode' => $data['AccountCode'] ?? null,
            'debit' => isset($data['Debit']) ? (float) $data['Debit'] : null,
            'credit' => isset($data['Credit']) ? (float) $data['Credit'] : null,
            'fcDebit' => isset($data['FCDebit']) ? (float) $data['FCDebit'] : null,
            'fcCredit' => isset($data['FCCredit']) ? (float) $data['FCCredit'] : null,
            'fcCurrency' => $data['FCCurrency'] ?? null,
            'dueDate' => $data['DueDate'] ?? null,
            'shortName' => $data['ShortName'] ?? null,
            'contraAccount' => $data['ContraAccount'] ?? null,
            'lineMemo' => $data['LineMemo'] ?? null,
            'referenceDate1' => $data['ReferenceDate1'] ?? null,
            'referenceDate2' => $data['ReferenceDate2'] ?? null,
            'reference1' => $data['Reference1'] ?? null,
            'reference2' => $data['Reference2'] ?? null,
            'costingCode' => $data['CostingCode'] ?? null,
            'costingCode2' => $data['CostingCode2'] ?? null,
            'costingCode3' => $data['CostingCode3'] ?? null,
            'costingCode4' => $data['CostingCode4'] ?? null,
            'costingCode5' => $data['CostingCode5'] ?? null,
            'projectCode' => $data['ProjectCode'] ?? null,
            'taxDate' => $data['TaxDate'] ?? null,
            'baseSum' => isset($data['BaseSum']) ? (string) $data['BaseSum'] : null,
            'taxGroup' => $data['TaxGroup'] ?? null,
            'taxCode' => $data['TaxCode'] ?? null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'Line_ID' => $this->lineId,
            'AccountCode' => $this->accountCode,
            'Debit' => $this->debit,
            'Credit' => $this->credit,
            'FCDebit' => $this->fcDebit,
            'FCCredit' => $this->fcCredit,
            'FCCurrency' => $this->fcCurrency,
            'DueDate' => $this->dueDate,
            'ShortName' => $this->shortName,
            'ContraAccount' => $this->contraAccount,
            'LineMemo' => $this->lineMemo,
            'ReferenceDate1' => $this->referenceDate1,
            'ReferenceDate2' => $this->referenceDate2,
            'Reference1' => $this->reference1,
            'Reference2' => $this->reference2,
            'CostingCode' => $this->costingCode,
            'CostingCode2' => $this->costingCode2,
            'CostingCode3' => $this->costingCode3,
            'CostingCode4' => $this->costingCode4,
            'CostingCode5' => $this->costingCode5,
            'ProjectCode' => $this->projectCode,
            'TaxDate' => $this->taxDate,
            'BaseSum' => $this->baseSum,
            'TaxGroup' => $this->taxGroup,
            'TaxCode' => $this->taxCode,
        ], fn ($value) => $value !== null);
    }
}

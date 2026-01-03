<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Finance;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class DepositDto extends BaseDto
{
    /**
     * @param  array<DepositCheckDto>  $checks
     * @param  array<DepositCreditCardDto>  $creditCards
     */
    public function __construct(
        public readonly ?int $absEntry = null,
        public readonly ?string $depositType = null,
        public readonly ?string $depositDate = null,
        public readonly ?string $depositCurrency = null,
        public readonly ?string $depositAccount = null,
        public readonly ?string $depositedBy = null,
        public readonly ?string $reconciled = null,
        public readonly ?float $journalRemarks = null,
        public readonly ?float $totalLC = null,
        public readonly ?float $totalFC = null,
        public readonly ?float $totalSC = null,
        public readonly ?string $allocationAccount = null,
        public readonly ?int $docNumber = null,
        public readonly ?string $bankReference = null,
        public readonly ?string $bankAccountNum = null,
        public readonly ?int $branchNumber = null,
        public readonly ?string $bankName = null,
        public readonly ?string $commissionAccount = null,
        public readonly ?float $commissionAmount = null,
        public readonly ?float $commissionAmountFC = null,
        public readonly ?string $canceled = null,
        public readonly array $checks = [],
        public readonly array $creditCards = [],
    ) {}

    protected static function mapFromArray(array $data): array
    {
        $checks = [];
        if (isset($data['Checks']) && is_array($data['Checks'])) {
            foreach ($data['Checks'] as $check) {
                $checks[] = DepositCheckDto::fromArray($check);
            }
        }

        $creditCards = [];
        if (isset($data['CreditCards']) && is_array($data['CreditCards'])) {
            foreach ($data['CreditCards'] as $card) {
                $creditCards[] = DepositCreditCardDto::fromArray($card);
            }
        }

        return [
            'absEntry' => isset($data['AbsEntry']) ? (int) $data['AbsEntry'] : null,
            'depositType' => $data['DepositType'] ?? null,
            'depositDate' => $data['DepositDate'] ?? null,
            'depositCurrency' => $data['DepositCurrency'] ?? null,
            'depositAccount' => $data['DepositAccount'] ?? null,
            'depositedBy' => $data['DepositedBy'] ?? null,
            'reconciled' => $data['Reconciled'] ?? null,
            'journalRemarks' => isset($data['JournalRemarks']) ? (float) $data['JournalRemarks'] : null,
            'totalLC' => isset($data['TotalLC']) ? (float) $data['TotalLC'] : null,
            'totalFC' => isset($data['TotalFC']) ? (float) $data['TotalFC'] : null,
            'totalSC' => isset($data['TotalSC']) ? (float) $data['TotalSC'] : null,
            'allocationAccount' => $data['AllocationAccount'] ?? null,
            'docNumber' => isset($data['DocNumber']) ? (int) $data['DocNumber'] : null,
            'bankReference' => $data['BankReference'] ?? null,
            'bankAccountNum' => $data['BankAccountNum'] ?? null,
            'branchNumber' => isset($data['BranchNumber']) ? (int) $data['BranchNumber'] : null,
            'bankName' => $data['BankName'] ?? null,
            'commissionAccount' => $data['CommissionAccount'] ?? null,
            'commissionAmount' => isset($data['CommissionAmount']) ? (float) $data['CommissionAmount'] : null,
            'commissionAmountFC' => isset($data['CommissionAmountFC']) ? (float) $data['CommissionAmountFC'] : null,
            'canceled' => $data['Canceled'] ?? null,
            'checks' => $checks,
            'creditCards' => $creditCards,
        ];
    }

    public function toArray(): array
    {
        $data = array_filter([
            'AbsEntry' => $this->absEntry,
            'DepositType' => $this->depositType,
            'DepositDate' => $this->depositDate,
            'DepositCurrency' => $this->depositCurrency,
            'DepositAccount' => $this->depositAccount,
            'DepositedBy' => $this->depositedBy,
            'Reconciled' => $this->reconciled,
            'JournalRemarks' => $this->journalRemarks,
            'TotalLC' => $this->totalLC,
            'TotalFC' => $this->totalFC,
            'TotalSC' => $this->totalSC,
            'AllocationAccount' => $this->allocationAccount,
            'DocNumber' => $this->docNumber,
            'BankReference' => $this->bankReference,
            'BankAccountNum' => $this->bankAccountNum,
            'BranchNumber' => $this->branchNumber,
            'BankName' => $this->bankName,
            'CommissionAccount' => $this->commissionAccount,
            'CommissionAmount' => $this->commissionAmount,
            'CommissionAmountFC' => $this->commissionAmountFC,
            'Canceled' => $this->canceled,
        ], fn ($value) => $value !== null);

        if (! empty($this->checks)) {
            $data['Checks'] = array_map(fn (DepositCheckDto $check) => $check->toArray(), $this->checks);
        }

        if (! empty($this->creditCards)) {
            $data['CreditCards'] = array_map(fn (DepositCreditCardDto $card) => $card->toArray(), $this->creditCards);
        }

        return $data;
    }
}

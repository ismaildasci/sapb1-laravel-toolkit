<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Finance;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class InternalReconciliationDto extends BaseDto
{
    public function __construct(
        public readonly ?int $reconNum = null,
        public readonly ?string $reconDate = null,
        public readonly ?string $cardOrAccount = null,
        public readonly ?float $reconSum = null,
        public readonly ?float $reconSumFC = null,
        public readonly ?float $reconSumSC = null,
        public readonly ?string $accountCode = null,
        public readonly ?string $cardCode = null,
        public readonly ?string $cardName = null,
        public readonly ?float $totalDiff = null,
        public readonly ?float $totalDiffFC = null,
        public readonly ?float $totalDiffSC = null,
        public readonly ?string $reconciliationType = null,
        public readonly ?string $isCanceled = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'reconNum' => isset($data['ReconNum']) ? (int) $data['ReconNum'] : null,
            'reconDate' => $data['ReconDate'] ?? null,
            'cardOrAccount' => $data['CardOrAccount'] ?? null,
            'reconSum' => isset($data['ReconSum']) ? (float) $data['ReconSum'] : null,
            'reconSumFC' => isset($data['ReconSumFC']) ? (float) $data['ReconSumFC'] : null,
            'reconSumSC' => isset($data['ReconSumSC']) ? (float) $data['ReconSumSC'] : null,
            'accountCode' => $data['AccountCode'] ?? null,
            'cardCode' => $data['CardCode'] ?? null,
            'cardName' => $data['CardName'] ?? null,
            'totalDiff' => isset($data['TotalDiff']) ? (float) $data['TotalDiff'] : null,
            'totalDiffFC' => isset($data['TotalDiffFC']) ? (float) $data['TotalDiffFC'] : null,
            'totalDiffSC' => isset($data['TotalDiffSC']) ? (float) $data['TotalDiffSC'] : null,
            'reconciliationType' => $data['ReconciliationType'] ?? null,
            'isCanceled' => $data['IsCanceled'] ?? null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'ReconNum' => $this->reconNum,
            'ReconDate' => $this->reconDate,
            'CardOrAccount' => $this->cardOrAccount,
            'ReconSum' => $this->reconSum,
            'ReconSumFC' => $this->reconSumFC,
            'ReconSumSC' => $this->reconSumSC,
            'AccountCode' => $this->accountCode,
            'CardCode' => $this->cardCode,
            'CardName' => $this->cardName,
            'TotalDiff' => $this->totalDiff,
            'TotalDiffFC' => $this->totalDiffFC,
            'TotalDiffSC' => $this->totalDiffSC,
            'ReconciliationType' => $this->reconciliationType,
            'IsCanceled' => $this->isCanceled,
        ], fn ($value) => $value !== null);
    }
}

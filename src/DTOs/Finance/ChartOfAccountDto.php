<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Finance;

use SapB1\Toolkit\DTOs\Base\BaseDto;
use SapB1\Toolkit\Enums\AccountType;
use SapB1\Toolkit\Enums\BoYesNo;

/**
 * @phpstan-consistent-constructor
 */
final class ChartOfAccountDto extends BaseDto
{
    public function __construct(
        public readonly ?string $code = null,
        public readonly ?string $name = null,
        public readonly ?string $foreignName = null,
        public readonly ?AccountType $accountType = null,
        public readonly ?string $fatherAccountKey = null,
        public readonly ?BoYesNo $activeAccount = null,
        public readonly ?string $accountLevel = null,
        public readonly ?string $dataSourcre = null,
        public readonly ?float $balance = null,
        public readonly ?float $balanceFc = null,
        public readonly ?float $balanceSc = null,
        public readonly ?string $locManTran = null,
        public readonly ?string $rateDifferences = null,
        public readonly ?string $costCentreCode = null,
        public readonly ?string $projectCode = null,
        public readonly ?string $defaultVATGroup = null,
        public readonly ?BoYesNo $cashAccount = null,
        public readonly ?BoYesNo $controlAccount = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'code' => $data['Code'] ?? null,
            'name' => $data['Name'] ?? null,
            'foreignName' => $data['ForeignName'] ?? null,
            'accountType' => isset($data['AccountType']) ? AccountType::tryFrom($data['AccountType']) : null,
            'fatherAccountKey' => $data['FatherAccountKey'] ?? null,
            'activeAccount' => isset($data['ActiveAccount']) ? BoYesNo::tryFrom($data['ActiveAccount']) : null,
            'accountLevel' => isset($data['AccountLevel']) ? (string) $data['AccountLevel'] : null,
            'dataSourcre' => $data['DataSource'] ?? null,
            'balance' => isset($data['Balance']) ? (float) $data['Balance'] : null,
            'balanceFc' => isset($data['BalanceFC']) ? (float) $data['BalanceFC'] : null,
            'balanceSc' => isset($data['BalanceSC']) ? (float) $data['BalanceSC'] : null,
            'locManTran' => $data['LocManTran'] ?? null,
            'rateDifferences' => $data['RateDifferences'] ?? null,
            'costCentreCode' => $data['CostCentreCode'] ?? null,
            'projectCode' => $data['ProjectCode'] ?? null,
            'defaultVATGroup' => $data['DefaultVATGroup'] ?? null,
            'cashAccount' => isset($data['CashAccount']) ? BoYesNo::tryFrom($data['CashAccount']) : null,
            'controlAccount' => isset($data['ControlAccount']) ? BoYesNo::tryFrom($data['ControlAccount']) : null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'Code' => $this->code,
            'Name' => $this->name,
            'ForeignName' => $this->foreignName,
            'AccountType' => $this->accountType?->value,
            'FatherAccountKey' => $this->fatherAccountKey,
            'ActiveAccount' => $this->activeAccount?->value,
            'AccountLevel' => $this->accountLevel,
            'DataSource' => $this->dataSourcre,
            'Balance' => $this->balance,
            'BalanceFC' => $this->balanceFc,
            'BalanceSC' => $this->balanceSc,
            'LocManTran' => $this->locManTran,
            'RateDifferences' => $this->rateDifferences,
            'CostCentreCode' => $this->costCentreCode,
            'ProjectCode' => $this->projectCode,
            'DefaultVATGroup' => $this->defaultVATGroup,
            'CashAccount' => $this->cashAccount?->value,
            'ControlAccount' => $this->controlAccount?->value,
        ], fn ($value) => $value !== null);
    }
}

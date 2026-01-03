<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Finance;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class BankDto extends BaseDto
{
    public function __construct(
        public readonly ?string $bankCode = null,
        public readonly ?string $bankName = null,
        public readonly ?string $accountforOutgoingChecks = null,
        public readonly ?string $branchforOutgoingChecks = null,
        public readonly ?string $nextCheckNumber = null,
        public readonly ?string $swiftNo = null,
        public readonly ?string $iSCN = null,
        public readonly ?string $countryCode = null,
        public readonly ?string $postOffice = null,
        public readonly ?string $absoluteEntry = null,
        public readonly ?string $defaultBankAccountKey = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'bankCode' => $data['BankCode'] ?? null,
            'bankName' => $data['BankName'] ?? null,
            'accountforOutgoingChecks' => $data['AccountforOutgoingChecks'] ?? null,
            'branchforOutgoingChecks' => $data['BranchforOutgoingChecks'] ?? null,
            'nextCheckNumber' => $data['NextCheckNumber'] ?? null,
            'swiftNo' => $data['SwiftNo'] ?? null,
            'iSCN' => $data['ISCN'] ?? null,
            'countryCode' => $data['CountryCode'] ?? null,
            'postOffice' => $data['PostOffice'] ?? null,
            'absoluteEntry' => $data['AbsoluteEntry'] ?? null,
            'defaultBankAccountKey' => $data['DefaultBankAccountKey'] ?? null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'BankCode' => $this->bankCode,
            'BankName' => $this->bankName,
            'AccountforOutgoingChecks' => $this->accountforOutgoingChecks,
            'BranchforOutgoingChecks' => $this->branchforOutgoingChecks,
            'NextCheckNumber' => $this->nextCheckNumber,
            'SwiftNo' => $this->swiftNo,
            'ISCN' => $this->iSCN,
            'CountryCode' => $this->countryCode,
            'PostOffice' => $this->postOffice,
            'AbsoluteEntry' => $this->absoluteEntry,
            'DefaultBankAccountKey' => $this->defaultBankAccountKey,
        ], fn ($value) => $value !== null);
    }
}

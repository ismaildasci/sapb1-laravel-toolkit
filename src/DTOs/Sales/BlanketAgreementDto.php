<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Sales;

use SapB1\Toolkit\DTOs\Base\BaseDto;
use SapB1\Toolkit\Enums\BlanketAgreementMethod;
use SapB1\Toolkit\Enums\BlanketAgreementStatus;

/**
 * Blanket Agreement DTO.
 *
 * @phpstan-consistent-constructor
 */
final class BlanketAgreementDto extends BaseDto
{
    /**
     * @param  array<BlanketAgreementItemLineDto>  $blanketAgreementItemsLines
     */
    public function __construct(
        public readonly ?int $agreementNo = null,
        public readonly ?string $bpCode = null,
        public readonly ?string $bpName = null,
        public readonly ?string $contactPersonCode = null,
        public readonly ?string $startDate = null,
        public readonly ?string $endDate = null,
        public readonly ?string $terminateDate = null,
        public readonly ?string $description = null,
        public readonly ?BlanketAgreementMethod $agreementMethod = null,
        public readonly ?BlanketAgreementStatus $status = null,
        public readonly ?string $owner = null,
        public readonly ?int $priceList = null,
        public readonly ?string $signingDate = null,
        public readonly ?string $remarks = null,
        public readonly ?float $renewalValue = null,
        public readonly array $blanketAgreementItemsLines = [],
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'agreementNo' => $data['AgreementNo'] ?? null,
            'bpCode' => $data['BPCode'] ?? null,
            'bpName' => $data['BPName'] ?? null,
            'contactPersonCode' => $data['ContactPersonCode'] ?? null,
            'startDate' => $data['StartDate'] ?? null,
            'endDate' => $data['EndDate'] ?? null,
            'terminateDate' => $data['TerminateDate'] ?? null,
            'description' => $data['Description'] ?? null,
            'agreementMethod' => isset($data['AgreementMethod']) ? BlanketAgreementMethod::tryFrom($data['AgreementMethod']) : null,
            'status' => isset($data['Status']) ? BlanketAgreementStatus::tryFrom($data['Status']) : null,
            'owner' => $data['Owner'] ?? null,
            'priceList' => $data['PriceList'] ?? null,
            'signingDate' => $data['SigningDate'] ?? null,
            'remarks' => $data['Remarks'] ?? null,
            'renewalValue' => $data['RenewalValue'] ?? null,
            'blanketAgreementItemsLines' => array_map(
                fn (array $line) => BlanketAgreementItemLineDto::fromArray($line),
                $data['BlanketAgreementItemsLines'] ?? []
            ),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = [];

        if ($this->agreementNo !== null) {
            $data['AgreementNo'] = $this->agreementNo;
        }

        if ($this->bpCode !== null) {
            $data['BPCode'] = $this->bpCode;
        }

        if ($this->bpName !== null) {
            $data['BPName'] = $this->bpName;
        }

        if ($this->contactPersonCode !== null) {
            $data['ContactPersonCode'] = $this->contactPersonCode;
        }

        if ($this->startDate !== null) {
            $data['StartDate'] = $this->startDate;
        }

        if ($this->endDate !== null) {
            $data['EndDate'] = $this->endDate;
        }

        if ($this->terminateDate !== null) {
            $data['TerminateDate'] = $this->terminateDate;
        }

        if ($this->description !== null) {
            $data['Description'] = $this->description;
        }

        if ($this->agreementMethod !== null) {
            $data['AgreementMethod'] = $this->agreementMethod->value;
        }

        if ($this->status !== null) {
            $data['Status'] = $this->status->value;
        }

        if ($this->owner !== null) {
            $data['Owner'] = $this->owner;
        }

        if ($this->priceList !== null) {
            $data['PriceList'] = $this->priceList;
        }

        if ($this->signingDate !== null) {
            $data['SigningDate'] = $this->signingDate;
        }

        if ($this->remarks !== null) {
            $data['Remarks'] = $this->remarks;
        }

        if ($this->renewalValue !== null) {
            $data['RenewalValue'] = $this->renewalValue;
        }

        if (! empty($this->blanketAgreementItemsLines)) {
            $data['BlanketAgreementItemsLines'] = array_map(
                fn (BlanketAgreementItemLineDto $line) => $line->toArray(),
                $this->blanketAgreementItemsLines
            );
        }

        return $data;
    }

    public function isActive(): bool
    {
        return $this->status !== null && $this->status->isActive();
    }

    public function isApproved(): bool
    {
        return $this->status !== null && $this->status->isApproved();
    }
}

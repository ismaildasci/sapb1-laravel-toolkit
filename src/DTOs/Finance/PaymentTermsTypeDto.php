<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Finance;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class PaymentTermsTypeDto extends BaseDto
{
    public function __construct(
        public readonly ?int $groupNumber = null,
        public readonly ?string $paymentTermsGroupName = null,
        public readonly ?string $startFrom = null,
        public readonly ?int $numberOfAdditionalMonths = null,
        public readonly ?int $numberOfAdditionalDays = null,
        public readonly ?string $creditLimit = null,
        public readonly ?string $generalDiscount = null,
        public readonly ?string $interestOnArrears = null,
        public readonly ?int $priceListNo = null,
        public readonly ?string $loadLimit = null,
        public readonly ?string $openReceipt = null,
        public readonly ?string $discountCode = null,
        public readonly ?int $dunningCode = null,
        public readonly ?int $baselineDate = null,
        public readonly ?int $numberOfToleranceDays = null,
        public readonly ?int $numberOfInstallments = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'groupNumber' => isset($data['GroupNumber']) ? (int) $data['GroupNumber'] : null,
            'paymentTermsGroupName' => $data['PaymentTermsGroupName'] ?? null,
            'startFrom' => $data['StartFrom'] ?? null,
            'numberOfAdditionalMonths' => isset($data['NumberOfAdditionalMonths']) ? (int) $data['NumberOfAdditionalMonths'] : null,
            'numberOfAdditionalDays' => isset($data['NumberOfAdditionalDays']) ? (int) $data['NumberOfAdditionalDays'] : null,
            'creditLimit' => $data['CreditLimit'] ?? null,
            'generalDiscount' => $data['GeneralDiscount'] ?? null,
            'interestOnArrears' => $data['InterestOnArrears'] ?? null,
            'priceListNo' => isset($data['PriceListNo']) ? (int) $data['PriceListNo'] : null,
            'loadLimit' => $data['LoadLimit'] ?? null,
            'openReceipt' => $data['OpenReceipt'] ?? null,
            'discountCode' => $data['DiscountCode'] ?? null,
            'dunningCode' => isset($data['DunningCode']) ? (int) $data['DunningCode'] : null,
            'baselineDate' => isset($data['BaselineDate']) ? (int) $data['BaselineDate'] : null,
            'numberOfToleranceDays' => isset($data['NumberOfToleranceDays']) ? (int) $data['NumberOfToleranceDays'] : null,
            'numberOfInstallments' => isset($data['NumberOfInstallments']) ? (int) $data['NumberOfInstallments'] : null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'GroupNumber' => $this->groupNumber,
            'PaymentTermsGroupName' => $this->paymentTermsGroupName,
            'StartFrom' => $this->startFrom,
            'NumberOfAdditionalMonths' => $this->numberOfAdditionalMonths,
            'NumberOfAdditionalDays' => $this->numberOfAdditionalDays,
            'CreditLimit' => $this->creditLimit,
            'GeneralDiscount' => $this->generalDiscount,
            'InterestOnArrears' => $this->interestOnArrears,
            'PriceListNo' => $this->priceListNo,
            'LoadLimit' => $this->loadLimit,
            'OpenReceipt' => $this->openReceipt,
            'DiscountCode' => $this->discountCode,
            'DunningCode' => $this->dunningCode,
            'BaselineDate' => $this->baselineDate,
            'NumberOfToleranceDays' => $this->numberOfToleranceDays,
            'NumberOfInstallments' => $this->numberOfInstallments,
        ], fn ($value) => $value !== null);
    }
}

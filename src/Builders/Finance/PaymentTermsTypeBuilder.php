<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Finance;

use SapB1\Toolkit\Builders\BaseBuilder;

/**
 * @phpstan-consistent-constructor
 */
final class PaymentTermsTypeBuilder extends BaseBuilder
{
    public function paymentTermsGroupName(string $name): static
    {
        return $this->set('PaymentTermsGroupName', $name);
    }

    public function startFrom(string $startFrom): static
    {
        return $this->set('StartFrom', $startFrom);
    }

    public function numberOfAdditionalMonths(int $months): static
    {
        return $this->set('NumberOfAdditionalMonths', $months);
    }

    public function numberOfAdditionalDays(int $days): static
    {
        return $this->set('NumberOfAdditionalDays', $days);
    }

    public function creditLimit(string $limit): static
    {
        return $this->set('CreditLimit', $limit);
    }

    public function generalDiscount(string $discount): static
    {
        return $this->set('GeneralDiscount', $discount);
    }

    public function interestOnArrears(string $interest): static
    {
        return $this->set('InterestOnArrears', $interest);
    }

    public function priceListNo(int $priceList): static
    {
        return $this->set('PriceListNo', $priceList);
    }

    public function loadLimit(string $limit): static
    {
        return $this->set('LoadLimit', $limit);
    }

    public function openReceipt(string $value): static
    {
        return $this->set('OpenReceipt', $value);
    }

    public function discountCode(string $code): static
    {
        return $this->set('DiscountCode', $code);
    }

    public function dunningCode(int $code): static
    {
        return $this->set('DunningCode', $code);
    }

    public function baselineDate(int $date): static
    {
        return $this->set('BaselineDate', $date);
    }

    public function numberOfToleranceDays(int $days): static
    {
        return $this->set('NumberOfToleranceDays', $days);
    }

    public function numberOfInstallments(int $installments): static
    {
        return $this->set('NumberOfInstallments', $installments);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Sales;

use SapB1\Toolkit\Builders\BaseBuilder;
use SapB1\Toolkit\Enums\BlanketAgreementMethod;
use SapB1\Toolkit\Enums\BlanketAgreementStatus;

/**
 * Builder for Blanket Agreements.
 *
 * @phpstan-consistent-constructor
 */
final class BlanketAgreementBuilder extends BaseBuilder
{
    public function bpCode(string $bpCode): static
    {
        return $this->set('BPCode', $bpCode);
    }

    public function bpName(string $bpName): static
    {
        return $this->set('BPName', $bpName);
    }

    public function contactPersonCode(string $code): static
    {
        return $this->set('ContactPersonCode', $code);
    }

    public function startDate(string $date): static
    {
        return $this->set('StartDate', $date);
    }

    public function endDate(string $date): static
    {
        return $this->set('EndDate', $date);
    }

    public function signingDate(string $date): static
    {
        return $this->set('SigningDate', $date);
    }

    public function description(string $description): static
    {
        return $this->set('Description', $description);
    }

    public function agreementMethod(BlanketAgreementMethod $method): static
    {
        return $this->set('AgreementMethod', $method->value);
    }

    public function status(BlanketAgreementStatus $status): static
    {
        return $this->set('Status', $status->value);
    }

    public function owner(string $owner): static
    {
        return $this->set('Owner', $owner);
    }

    public function priceList(int $priceList): static
    {
        return $this->set('PriceList', $priceList);
    }

    public function remarks(string $remarks): static
    {
        return $this->set('Remarks', $remarks);
    }

    public function renewalValue(float $value): static
    {
        return $this->set('RenewalValue', $value);
    }

    /**
     * Set as monetary agreement.
     */
    public function asMonetary(): static
    {
        return $this->agreementMethod(BlanketAgreementMethod::Monetary);
    }

    /**
     * Set as quantity agreement.
     */
    public function asQuantity(): static
    {
        return $this->agreementMethod(BlanketAgreementMethod::Quantity);
    }

    /**
     * @param  array<array<string, mixed>>  $lines
     */
    public function itemLines(array $lines): static
    {
        return $this->set('BlanketAgreementItemsLines', $lines);
    }

    /**
     * @param  array<string, mixed>  $line
     */
    public function addItemLine(array $line): static
    {
        $lines = $this->get('BlanketAgreementItemsLines', []);
        $lines[] = $line;

        return $this->set('BlanketAgreementItemsLines', $lines);
    }

    /**
     * Build and return the final data array.
     *
     * @return array<string, mixed>
     */
    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

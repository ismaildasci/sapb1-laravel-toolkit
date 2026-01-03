<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Finance;

use SapB1\Toolkit\Builders\BaseBuilder;
use SapB1\Toolkit\DTOs\Finance\DepositCheckDto;
use SapB1\Toolkit\DTOs\Finance\DepositCreditCardDto;

/**
 * @phpstan-consistent-constructor
 */
final class DepositBuilder extends BaseBuilder
{
    public function depositType(string $type): static
    {
        return $this->set('DepositType', $type);
    }

    public function depositDate(string $date): static
    {
        return $this->set('DepositDate', $date);
    }

    public function depositCurrency(string $currency): static
    {
        return $this->set('DepositCurrency', $currency);
    }

    public function depositAccount(string $account): static
    {
        return $this->set('DepositAccount', $account);
    }

    public function depositedBy(string $by): static
    {
        return $this->set('DepositedBy', $by);
    }

    public function allocationAccount(string $account): static
    {
        return $this->set('AllocationAccount', $account);
    }

    public function bankReference(string $reference): static
    {
        return $this->set('BankReference', $reference);
    }

    public function bankAccountNum(string $num): static
    {
        return $this->set('BankAccountNum', $num);
    }

    public function branchNumber(int $number): static
    {
        return $this->set('BranchNumber', $number);
    }

    public function bankName(string $name): static
    {
        return $this->set('BankName', $name);
    }

    public function commissionAccount(string $account): static
    {
        return $this->set('CommissionAccount', $account);
    }

    public function commissionAmount(float $amount): static
    {
        return $this->set('CommissionAmount', $amount);
    }

    /**
     * @param  array<DepositCheckDto|array<string, mixed>>  $checks
     */
    public function checks(array $checks): static
    {
        $mapped = array_map(fn ($check) => $check instanceof DepositCheckDto ? $check->toArray() : $check, $checks);

        return $this->set('Checks', $mapped);
    }

    /**
     * @param  DepositCheckDto|array<string, mixed>  $check
     */
    public function addCheck(DepositCheckDto|array $check): static
    {
        $checks = $this->get('Checks', []);
        $checks[] = $check instanceof DepositCheckDto ? $check->toArray() : $check;

        return $this->set('Checks', $checks);
    }

    /**
     * @param  array<DepositCreditCardDto|array<string, mixed>>  $cards
     */
    public function creditCards(array $cards): static
    {
        $mapped = array_map(fn ($card) => $card instanceof DepositCreditCardDto ? $card->toArray() : $card, $cards);

        return $this->set('CreditCards', $mapped);
    }

    /**
     * @param  DepositCreditCardDto|array<string, mixed>  $card
     */
    public function addCreditCard(DepositCreditCardDto|array $card): static
    {
        $cards = $this->get('CreditCards', []);
        $cards[] = $card instanceof DepositCreditCardDto ? $card->toArray() : $card;

        return $this->set('CreditCards', $cards);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

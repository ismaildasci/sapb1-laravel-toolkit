<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Finance;

use SapB1\Toolkit\Builders\BaseBuilder;
use SapB1\Toolkit\DTOs\Finance\BankStatementRowDto;

/**
 * @phpstan-consistent-constructor
 */
final class BankStatementBuilder extends BaseBuilder
{
    public function bankAccountKey(string $key): static
    {
        return $this->set('BankAccountKey', $key);
    }

    public function statementNumber(string $number): static
    {
        return $this->set('StatementNumber', $number);
    }

    public function statementDate(string $date): static
    {
        return $this->set('StatementDate', $date);
    }

    public function status(string $status): static
    {
        return $this->set('Status', $status);
    }

    public function startingBalanceF(float $balance): static
    {
        return $this->set('StartingBalanceF', $balance);
    }

    public function endingBalanceF(float $balance): static
    {
        return $this->set('EndingBalanceF', $balance);
    }

    public function currency(string $currency): static
    {
        return $this->set('Currency', $currency);
    }

    public function startingBalanceL(float $balance): static
    {
        return $this->set('StartingBalanceL', $balance);
    }

    public function endingBalanceL(float $balance): static
    {
        return $this->set('EndingBalanceL', $balance);
    }

    /**
     * @param  array<BankStatementRowDto|array<string, mixed>>  $rows
     */
    public function bankStatementRows(array $rows): static
    {
        $mapped = array_map(fn ($row) => $row instanceof BankStatementRowDto ? $row->toArray() : $row, $rows);

        return $this->set('BankStatementRows', $mapped);
    }

    /**
     * @param  BankStatementRowDto|array<string, mixed>  $row
     */
    public function addRow(BankStatementRowDto|array $row): static
    {
        $rows = $this->get('BankStatementRows', []);
        $rows[] = $row instanceof BankStatementRowDto ? $row->toArray() : $row;

        return $this->set('BankStatementRows', $rows);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

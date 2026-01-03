<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Finance;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class BankStatementDto extends BaseDto
{
    /**
     * @param  array<BankStatementRowDto>  $bankStatementRows
     */
    public function __construct(
        public readonly ?int $internalNumber = null,
        public readonly ?string $bankAccountKey = null,
        public readonly ?string $statementNumber = null,
        public readonly ?string $statementDate = null,
        public readonly ?string $status = null,
        public readonly ?string $imported = null,
        public readonly ?float $startingBalanceF = null,
        public readonly ?float $endingBalanceF = null,
        public readonly ?string $currency = null,
        public readonly ?float $startingBalanceL = null,
        public readonly ?float $endingBalanceL = null,
        public readonly array $bankStatementRows = [],
    ) {}

    protected static function mapFromArray(array $data): array
    {
        $rows = [];
        if (isset($data['BankStatementRows']) && is_array($data['BankStatementRows'])) {
            foreach ($data['BankStatementRows'] as $row) {
                $rows[] = BankStatementRowDto::fromArray($row);
            }
        }

        return [
            'internalNumber' => isset($data['InternalNumber']) ? (int) $data['InternalNumber'] : null,
            'bankAccountKey' => $data['BankAccountKey'] ?? null,
            'statementNumber' => $data['StatementNumber'] ?? null,
            'statementDate' => $data['StatementDate'] ?? null,
            'status' => $data['Status'] ?? null,
            'imported' => $data['Imported'] ?? null,
            'startingBalanceF' => isset($data['StartingBalanceF']) ? (float) $data['StartingBalanceF'] : null,
            'endingBalanceF' => isset($data['EndingBalanceF']) ? (float) $data['EndingBalanceF'] : null,
            'currency' => $data['Currency'] ?? null,
            'startingBalanceL' => isset($data['StartingBalanceL']) ? (float) $data['StartingBalanceL'] : null,
            'endingBalanceL' => isset($data['EndingBalanceL']) ? (float) $data['EndingBalanceL'] : null,
            'bankStatementRows' => $rows,
        ];
    }

    public function toArray(): array
    {
        $data = array_filter([
            'InternalNumber' => $this->internalNumber,
            'BankAccountKey' => $this->bankAccountKey,
            'StatementNumber' => $this->statementNumber,
            'StatementDate' => $this->statementDate,
            'Status' => $this->status,
            'Imported' => $this->imported,
            'StartingBalanceF' => $this->startingBalanceF,
            'EndingBalanceF' => $this->endingBalanceF,
            'Currency' => $this->currency,
            'StartingBalanceL' => $this->startingBalanceL,
            'EndingBalanceL' => $this->endingBalanceL,
        ], fn ($value) => $value !== null);

        if (! empty($this->bankStatementRows)) {
            $data['BankStatementRows'] = array_map(fn (BankStatementRowDto $row) => $row->toArray(), $this->bankStatementRows);
        }

        return $data;
    }
}

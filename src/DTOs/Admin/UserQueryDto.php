<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Admin;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class UserQueryDto extends BaseDto
{
    public function __construct(
        public readonly ?int $internalKey = null,
        public readonly ?int $queryCategory = null,
        public readonly ?string $queryDescription = null,
        public readonly ?string $query = null,
        public readonly ?int $procedureAlias = null,
        public readonly ?string $procedureName = null,
        public readonly ?int $queryType = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'internalKey' => $data['InternalKey'] ?? null,
            'queryCategory' => $data['QueryCategory'] ?? null,
            'queryDescription' => $data['QueryDescription'] ?? null,
            'query' => $data['Query'] ?? null,
            'procedureAlias' => $data['ProcedureAlias'] ?? null,
            'procedureName' => $data['ProcedureName'] ?? null,
            'queryType' => $data['QueryType'] ?? null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'InternalKey' => $this->internalKey,
            'QueryCategory' => $this->queryCategory,
            'QueryDescription' => $this->queryDescription,
            'Query' => $this->query,
            'ProcedureAlias' => $this->procedureAlias,
            'ProcedureName' => $this->procedureName,
            'QueryType' => $this->queryType,
        ], fn ($value) => $value !== null);
    }
}

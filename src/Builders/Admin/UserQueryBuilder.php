<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Admin;

use SapB1\Toolkit\Builders\BaseBuilder;

/**
 * @phpstan-consistent-constructor
 */
final class UserQueryBuilder extends BaseBuilder
{
    public function queryCategory(int $category): static
    {
        return $this->set('QueryCategory', $category);
    }

    public function queryDescription(string $description): static
    {
        return $this->set('QueryDescription', $description);
    }

    public function query(string $query): static
    {
        return $this->set('Query', $query);
    }

    public function procedureAlias(int $alias): static
    {
        return $this->set('ProcedureAlias', $alias);
    }

    public function procedureName(string $name): static
    {
        return $this->set('ProcedureName', $name);
    }

    public function queryType(int $type): static
    {
        return $this->set('QueryType', $type);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Base;

use SapB1\Facades\SapB1;
use SapB1\Toolkit\Contracts\ActionInterface;

abstract class BaseAction implements ActionInterface
{
    protected string $entity = '';

    protected string $connection = 'default';

    public function connection(string $name): static
    {
        $this->connection = $name;

        return $this;
    }

    protected function client(): mixed
    {
        return SapB1::connection($this->connection);
    }

    abstract public function execute(mixed ...$args): mixed;
}

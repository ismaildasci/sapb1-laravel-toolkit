<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Services;

use SapB1\Facades\SapB1;
use SapB1\Toolkit\Contracts\ServiceInterface;

abstract class BaseService implements ServiceInterface
{
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
}

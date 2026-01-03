<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Exceptions;

class ConnectionException extends SapB1Exception
{
    public function __construct(
        string $message,
        public readonly string $connectionName = 'default',
        ?\Throwable $previous = null
    ) {
        parent::__construct(
            $message,
            503,
            ['connection' => $connectionName],
            $previous
        );
    }
}

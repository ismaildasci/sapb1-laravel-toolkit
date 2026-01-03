<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Exceptions;

class AuthenticationException extends SapB1Exception
{
    public function __construct(
        string $message = 'Authentication failed',
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, 401, [], $previous);
    }
}

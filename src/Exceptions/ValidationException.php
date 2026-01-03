<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Exceptions;

class ValidationException extends SapB1Exception
{
    /**
     * @param  array<string, array<string>>  $errors
     */
    public function __construct(
        string $message,
        public readonly array $errors = [],
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, 422, ['errors' => $errors], $previous);
    }

    /**
     * @return array<string, array<string>>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}

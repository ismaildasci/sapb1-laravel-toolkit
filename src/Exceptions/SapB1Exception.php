<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Exceptions;

use Exception;

/**
 * Base exception for all SAP B1 Toolkit exceptions.
 *
 * Provides additional context data for debugging and error handling.
 * All toolkit-specific exceptions should extend this class.
 */
class SapB1Exception extends Exception
{
    /**
     * @param  array<string, mixed>  $context
     */
    public function __construct(
        string $message,
        int $code = 0,
        public readonly array $context = [],
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return array<string, mixed>
     */
    public function getContext(): array
    {
        return $this->context;
    }
}

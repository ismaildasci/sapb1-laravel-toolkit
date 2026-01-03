<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Exceptions;

class DocumentNotFoundException extends SapB1Exception
{
    public function __construct(
        public readonly string $entity,
        public readonly int|string $identifier,
        ?\Throwable $previous = null
    ) {
        parent::__construct(
            "Document not found: {$entity} with ID {$identifier}",
            404,
            ['entity' => $entity, 'identifier' => $identifier],
            $previous
        );
    }
}

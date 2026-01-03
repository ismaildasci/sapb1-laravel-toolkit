<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Exceptions;

class DocumentClosedException extends SapB1Exception
{
    public function __construct(
        public readonly string $entity,
        public readonly int $docEntry,
        ?\Throwable $previous = null
    ) {
        parent::__construct(
            "Document is already closed: {$entity} #{$docEntry}",
            400,
            ['entity' => $entity, 'docEntry' => $docEntry],
            $previous
        );
    }
}

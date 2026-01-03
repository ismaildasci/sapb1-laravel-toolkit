<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Exceptions;

class ApprovalRequiredException extends SapB1Exception
{
    public function __construct(
        public readonly string $entity,
        public readonly int $docEntry,
        public readonly int $approvalRequestCode,
        ?\Throwable $previous = null
    ) {
        parent::__construct(
            "Document {$entity} #{$docEntry} requires approval (Request #{$approvalRequestCode})",
            403,
            [
                'entity' => $entity,
                'docEntry' => $docEntry,
                'approvalRequestCode' => $approvalRequestCode,
            ],
            $previous
        );
    }
}

<?php

declare(strict_types=1);

namespace SapB1\Toolkit\ChangeTracking;

/**
 * Types of changes that can be detected.
 */
enum ChangeType: string
{
    case Created = 'created';
    case Updated = 'updated';
    case Deleted = 'deleted';
}

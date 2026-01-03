<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \SapB1\Toolkit\Toolkit
 */
class Toolkit extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \SapB1\Toolkit\Toolkit::class;
    }
}

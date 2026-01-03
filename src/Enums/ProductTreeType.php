<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Enums;

enum ProductTreeType: string
{
    case Assembly = 'iProductionTree';
    case Template = 'iTemplateTree';
    case Sales = 'iSalesTree';

    public function label(): string
    {
        return match ($this) {
            self::Assembly => 'Production/Assembly',
            self::Template => 'Template',
            self::Sales => 'Sales',
        };
    }
}

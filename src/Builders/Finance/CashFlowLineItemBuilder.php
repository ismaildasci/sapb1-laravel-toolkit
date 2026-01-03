<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Finance;

use SapB1\Toolkit\Builders\BaseBuilder;

/**
 * @phpstan-consistent-constructor
 */
final class CashFlowLineItemBuilder extends BaseBuilder
{
    public function lineItemName(string $name): static
    {
        return $this->set('LineItemName', $name);
    }

    public function activeLineItem(string $active): static
    {
        return $this->set('ActiveLineItem', $active);
    }

    public function parentArticle(int $parent): static
    {
        return $this->set('ParentArticle', $parent);
    }

    public function level(int $level): static
    {
        return $this->set('Level', $level);
    }

    public function drawer(string $drawer): static
    {
        return $this->set('Drawer', $drawer);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}

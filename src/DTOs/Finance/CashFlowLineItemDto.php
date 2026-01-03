<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Finance;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class CashFlowLineItemDto extends BaseDto
{
    public function __construct(
        public readonly ?int $lineItemID = null,
        public readonly ?string $lineItemName = null,
        public readonly ?string $activeLineItem = null,
        public readonly ?int $parentArticle = null,
        public readonly ?int $level = null,
        public readonly ?string $drawer = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'lineItemID' => isset($data['LineItemID']) ? (int) $data['LineItemID'] : null,
            'lineItemName' => $data['LineItemName'] ?? null,
            'activeLineItem' => $data['ActiveLineItem'] ?? null,
            'parentArticle' => isset($data['ParentArticle']) ? (int) $data['ParentArticle'] : null,
            'level' => isset($data['Level']) ? (int) $data['Level'] : null,
            'drawer' => $data['Drawer'] ?? null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'LineItemID' => $this->lineItemID,
            'LineItemName' => $this->lineItemName,
            'ActiveLineItem' => $this->activeLineItem,
            'ParentArticle' => $this->parentArticle,
            'Level' => $this->level,
            'Drawer' => $this->drawer,
        ], fn ($value) => $value !== null);
    }
}

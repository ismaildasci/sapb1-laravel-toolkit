<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Tests\Integration;

use SapB1\Toolkit\Actions\Inventory\ItemAction;
use SapB1\Toolkit\DTOs\Inventory\ItemDto;

class ItemIntegrationTest extends IntegrationTestCase
{
    public function test_can_find_item(): void
    {
        $action = new ItemAction;
        $result = $action->find($this->getTestItemCode());

        $this->assertInstanceOf(ItemDto::class, $result);
        $this->assertEquals($this->getTestItemCode(), $result->itemCode);
    }

    public function test_can_get_item_as_dto(): void
    {
        $action = new ItemAction;
        $dto = $action->find($this->getTestItemCode());

        $this->assertInstanceOf(ItemDto::class, $dto);
        $this->assertEquals($this->getTestItemCode(), $dto->itemCode);
    }

    public function test_can_get_active_items(): void
    {
        $action = new ItemAction;
        $result = $action->getActive();

        $this->assertIsArray($result);
    }

    public function test_can_search_items(): void
    {
        $action = new ItemAction;
        $result = $action->search('');

        $this->assertIsArray($result);
    }
}

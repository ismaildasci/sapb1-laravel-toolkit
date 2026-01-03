<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Tests\Integration;

use SapB1\Toolkit\Actions\Sales\OrderAction;
use SapB1\Toolkit\Builders\Sales\OrderBuilder;
use SapB1\Toolkit\DTOs\Sales\OrderDto;

class OrderIntegrationTest extends IntegrationTestCase
{
    private ?int $createdDocEntry = null;

    protected function tearDown(): void
    {
        // Cancel the created order if exists
        if ($this->createdDocEntry !== null) {
            try {
                $action = new OrderAction;
                $action->cancel($this->createdDocEntry);
            } catch (\Throwable $e) {
                // Ignore cleanup errors
            }
        }

        parent::tearDown();
    }

    public function test_can_get_open_orders(): void
    {
        $action = new OrderAction;
        $result = $action->getOpen();

        $this->assertIsArray($result);
    }

    public function test_can_create_order(): void
    {
        $builder = OrderBuilder::create()
            ->cardCode($this->getTestCustomerCode())
            ->docDate(date('Y-m-d'))
            ->docDueDate(date('Y-m-d', strtotime('+7 days')))
            ->addLine([
                'ItemCode' => $this->getTestItemCode(),
                'Quantity' => 1,
                'WarehouseCode' => $this->getTestWarehouseCode(),
            ]);

        $action = new OrderAction;
        $result = $action->create($builder);

        $this->assertInstanceOf(OrderDto::class, $result);
        $this->assertNotNull($result->docEntry);
        $this->assertNotNull($result->docNum);

        $this->createdDocEntry = $result->docEntry;
    }

    public function test_can_find_order(): void
    {
        // First create an order
        $builder = OrderBuilder::create()
            ->cardCode($this->getTestCustomerCode())
            ->docDate(date('Y-m-d'))
            ->docDueDate(date('Y-m-d', strtotime('+7 days')))
            ->addLine([
                'ItemCode' => $this->getTestItemCode(),
                'Quantity' => 1,
                'WarehouseCode' => $this->getTestWarehouseCode(),
            ]);

        $action = new OrderAction;
        $created = $action->create($builder);
        $this->createdDocEntry = $created->docEntry;

        // Now find it
        $found = $action->find($this->createdDocEntry);

        $this->assertInstanceOf(OrderDto::class, $found);
        $this->assertEquals($this->createdDocEntry, $found->docEntry);
    }

    public function test_can_get_order_as_dto(): void
    {
        // First create an order
        $builder = OrderBuilder::create()
            ->cardCode($this->getTestCustomerCode())
            ->docDate(date('Y-m-d'))
            ->docDueDate(date('Y-m-d', strtotime('+7 days')))
            ->comments('Integration test order')
            ->addLine([
                'ItemCode' => $this->getTestItemCode(),
                'Quantity' => 2,
                'WarehouseCode' => $this->getTestWarehouseCode(),
            ]);

        $action = new OrderAction;
        $dto = $action->create($builder);
        $this->createdDocEntry = $dto->docEntry;

        $this->assertInstanceOf(OrderDto::class, $dto);
        $this->assertEquals($this->getTestCustomerCode(), $dto->cardCode);
        $this->assertEquals('Integration test order', $dto->comments);
    }

    public function test_can_update_order(): void
    {
        // First create an order
        $builder = OrderBuilder::create()
            ->cardCode($this->getTestCustomerCode())
            ->docDate(date('Y-m-d'))
            ->docDueDate(date('Y-m-d', strtotime('+7 days')))
            ->addLine([
                'ItemCode' => $this->getTestItemCode(),
                'Quantity' => 1,
                'WarehouseCode' => $this->getTestWarehouseCode(),
            ]);

        $action = new OrderAction;
        $created = $action->create($builder);
        $this->createdDocEntry = $created->docEntry;

        // Update comments
        $updated = $action->update($this->createdDocEntry, [
            'Comments' => 'Updated via integration test',
        ]);

        $this->assertInstanceOf(OrderDto::class, $updated);

        // Verify update
        $found = $action->find($this->createdDocEntry);
        $this->assertEquals('Updated via integration test', $found->comments);
    }

    public function test_can_cancel_order(): void
    {
        // First create an order
        $builder = OrderBuilder::create()
            ->cardCode($this->getTestCustomerCode())
            ->docDate(date('Y-m-d'))
            ->docDueDate(date('Y-m-d', strtotime('+7 days')))
            ->addLine([
                'ItemCode' => $this->getTestItemCode(),
                'Quantity' => 1,
                'WarehouseCode' => $this->getTestWarehouseCode(),
            ]);

        $action = new OrderAction;
        $created = $action->create($builder);
        $docEntry = $created->docEntry;

        // Cancel it
        $result = $action->cancel($docEntry);

        $this->assertTrue($result);

        // Verify cancelled - cancelled orders have Closed status
        $found = $action->find($docEntry);
        $this->assertEquals(\SapB1\Toolkit\Enums\DocumentStatus::Closed, $found->documentStatus);

        // Don't try to cancel again in tearDown
        $this->createdDocEntry = null;
    }
}

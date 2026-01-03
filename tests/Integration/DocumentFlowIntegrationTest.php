<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Tests\Integration;

use SapB1\Toolkit\Actions\Sales\DeliveryAction;
use SapB1\Toolkit\Actions\Sales\InvoiceAction;
use SapB1\Toolkit\Actions\Sales\OrderAction;
use SapB1\Toolkit\Builders\Sales\OrderBuilder;
use SapB1\Toolkit\Services\DocumentFlowService;

class DocumentFlowIntegrationTest extends IntegrationTestCase
{
    /**
     * @var array<int, array{type: string, docEntry: int}> Document entries to clean up
     */
    private array $createdDocuments = [];

    protected function tearDown(): void
    {
        // Cancel created documents in reverse order
        $actions = [
            'invoice' => new InvoiceAction,
            'delivery' => new DeliveryAction,
            'order' => new OrderAction,
        ];

        foreach (array_reverse($this->createdDocuments) as $doc) {
            try {
                $actions[$doc['type']]->cancel($doc['docEntry']);
            } catch (\Throwable $e) {
                // Ignore cleanup errors
            }
        }

        parent::tearDown();
    }

    public function test_can_convert_order_to_delivery(): void
    {
        // Create an order
        $builder = OrderBuilder::create()
            ->cardCode($this->getTestCustomerCode())
            ->docDate(date('Y-m-d'))
            ->addLine([
                'ItemCode' => $this->getTestItemCode(),
                'Quantity' => 1,
                'WarehouseCode' => $this->getTestWarehouseCode(),
            ]);

        $orderAction = new OrderAction;
        $order = $orderAction->create($builder);
        $this->createdDocuments[] = ['type' => 'order', 'docEntry' => $order->docEntry];

        // Convert to delivery
        $service = new DocumentFlowService;
        $delivery = $service->orderToDelivery($order->docEntry);

        $this->assertNotNull($delivery->docEntry);
        $this->assertEquals($this->getTestCustomerCode(), $delivery->cardCode);

        $this->createdDocuments[] = ['type' => 'delivery', 'docEntry' => $delivery->docEntry];
    }

    public function test_can_convert_order_to_invoice(): void
    {
        // Create an order
        $builder = OrderBuilder::create()
            ->cardCode($this->getTestCustomerCode())
            ->docDate(date('Y-m-d'))
            ->addLine([
                'ItemCode' => $this->getTestItemCode(),
                'Quantity' => 1,
                'WarehouseCode' => $this->getTestWarehouseCode(),
            ]);

        $orderAction = new OrderAction;
        $order = $orderAction->create($builder);
        $this->createdDocuments[] = ['type' => 'order', 'docEntry' => $order->docEntry];

        // Convert to invoice
        $service = new DocumentFlowService;
        $invoice = $service->orderToInvoice($order->docEntry);

        $this->assertNotNull($invoice->docEntry);
        $this->assertEquals($this->getTestCustomerCode(), $invoice->cardCode);

        $this->createdDocuments[] = ['type' => 'invoice', 'docEntry' => $invoice->docEntry];
    }

    public function test_can_get_order_flow(): void
    {
        // Create an order
        $builder = OrderBuilder::create()
            ->cardCode($this->getTestCustomerCode())
            ->docDate(date('Y-m-d'))
            ->addLine([
                'ItemCode' => $this->getTestItemCode(),
                'Quantity' => 1,
                'WarehouseCode' => $this->getTestWarehouseCode(),
            ]);

        $orderAction = new OrderAction;
        $order = $orderAction->create($builder);
        $this->createdDocuments[] = ['type' => 'order', 'docEntry' => $order->docEntry];

        // Get flow
        $service = new DocumentFlowService;
        $flow = $service->getOrderFlow($order->docEntry);

        $this->assertArrayHasKey('order', $flow);
        $this->assertArrayHasKey('deliveries', $flow);
        $this->assertArrayHasKey('invoices', $flow);
        $this->assertEquals($order->docEntry, $flow['order']['DocEntry']);
    }

    public function test_can_close_orders(): void
    {
        // Create two orders
        $builder1 = OrderBuilder::create()
            ->cardCode($this->getTestCustomerCode())
            ->docDate(date('Y-m-d'))
            ->addLine([
                'ItemCode' => $this->getTestItemCode(),
                'Quantity' => 1,
                'WarehouseCode' => $this->getTestWarehouseCode(),
            ]);

        $builder2 = OrderBuilder::create()
            ->cardCode($this->getTestCustomerCode())
            ->docDate(date('Y-m-d'))
            ->addLine([
                'ItemCode' => $this->getTestItemCode(),
                'Quantity' => 1,
                'WarehouseCode' => $this->getTestWarehouseCode(),
            ]);

        $orderAction = new OrderAction;
        $order1 = $orderAction->create($builder1);
        $order2 = $orderAction->create($builder2);

        $this->createdDocuments[] = ['type' => 'order', 'docEntry' => $order1->docEntry];
        $this->createdDocuments[] = ['type' => 'order', 'docEntry' => $order2->docEntry];

        // Close both orders
        $service = new DocumentFlowService;
        $results = $service->closeOrders([
            $order1->docEntry,
            $order2->docEntry,
        ]);

        $this->assertArrayHasKey($order1->docEntry, $results);
        $this->assertArrayHasKey($order2->docEntry, $results);
        $this->assertTrue($results[$order1->docEntry]);
        $this->assertTrue($results[$order2->docEntry]);

        // Clear cleanup list since orders are closed
        $this->createdDocuments = [];
    }
}

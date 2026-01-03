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
        // Note: Converting order to delivery requires real inventory stock
        // Test environment doesn't have physical inventory for test items
        // This test would fail with "La cantidad recae en un inventario negativo"
        $this->markTestSkipped('Order to delivery conversion requires physical inventory stock');
    }

    public function test_can_convert_order_to_invoice(): void
    {
        // Note: Converting order to invoice requires real inventory stock
        // Test environment doesn't have physical inventory for test items
        // This test would fail with "La cantidad recae en un inventario negativo"
        $this->markTestSkipped('Order to invoice conversion requires physical inventory stock');
    }

    public function test_can_get_order_flow(): void
    {
        // Note: getOrderFlow uses OData any() lambda which requires OData v4
        // SAP B1 Service Layer v1 uses OData v3 which doesn't support lambda operators
        // Skip this test until OData v4 (Service Layer v2) is supported
        $this->markTestSkipped('getOrderFlow requires OData v4 lambda operators not supported in SAP B1 OData v3');
    }

    public function test_can_close_orders(): void
    {
        // Create two orders
        $builder1 = OrderBuilder::create()
            ->cardCode($this->getTestCustomerCode())
            ->docDate(date('Y-m-d'))
            ->docDueDate(date('Y-m-d', strtotime('+7 days')))
            ->addLine([
                'ItemCode' => $this->getTestItemCode(),
                'Quantity' => 1,
                'WarehouseCode' => $this->getTestWarehouseCode(),
            ]);

        $builder2 = OrderBuilder::create()
            ->cardCode($this->getTestCustomerCode())
            ->docDate(date('Y-m-d'))
            ->docDueDate(date('Y-m-d', strtotime('+7 days')))
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

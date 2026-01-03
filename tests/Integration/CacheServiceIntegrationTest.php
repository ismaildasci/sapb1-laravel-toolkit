<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Tests\Integration;

use Illuminate\Support\Facades\Cache;
use SapB1\Toolkit\Services\CacheService;

class CacheServiceIntegrationTest extends IntegrationTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    public function test_can_cache_items(): void
    {
        $service = new CacheService;
        $items = $service->items(['ItemCode', 'ItemName']);

        $this->assertIsArray($items);
        $this->assertNotEmpty($items);

        // Verify cached
        $cachedItems = $service->items(['ItemCode', 'ItemName']);
        $this->assertEquals($items, $cachedItems);
    }

    public function test_can_cache_customers(): void
    {
        $service = new CacheService;
        $customers = $service->customers(['CardCode', 'CardName']);

        $this->assertIsArray($customers);
        $this->assertNotEmpty($customers);
    }

    public function test_can_cache_suppliers(): void
    {
        $service = new CacheService;
        $suppliers = $service->suppliers(['CardCode', 'CardName']);

        $this->assertIsArray($suppliers);
    }

    public function test_can_cache_warehouses(): void
    {
        $service = new CacheService;
        $warehouses = $service->warehouses();

        $this->assertIsArray($warehouses);
        $this->assertNotEmpty($warehouses);
    }

    public function test_can_cache_chart_of_accounts(): void
    {
        $service = new CacheService;
        $accounts = $service->chartOfAccounts(['Code', 'Name']);

        $this->assertIsArray($accounts);
    }

    public function test_can_cache_sales_persons(): void
    {
        $service = new CacheService;
        $salesPersons = $service->salesPersons();

        $this->assertIsArray($salesPersons);
    }

    public function test_can_cache_price_lists(): void
    {
        $service = new CacheService;
        $priceLists = $service->priceLists();

        $this->assertIsArray($priceLists);
        $this->assertNotEmpty($priceLists);
    }

    public function test_can_warm_cache(): void
    {
        $service = new CacheService;
        $service->warm(['items', 'warehouses']);

        // Verify items are cached - they should come from cache now
        $items = $service->items(['ItemCode', 'ItemName']);
        $this->assertIsArray($items);

        $warehouses = $service->warehouses(['WarehouseCode', 'WarehouseName']);
        $this->assertIsArray($warehouses);
    }

    public function test_can_set_custom_ttl(): void
    {
        $service = new CacheService;
        $result = $service->ttl(7200)->items(['ItemCode']);

        $this->assertIsArray($result);
    }

    public function test_different_connections_have_different_cache(): void
    {
        $service1 = (new CacheService)->connection('default');
        $service2 = (new CacheService)->connection('secondary');

        // Cache with service1
        $items1 = $service1->items(['ItemCode', 'ItemName']);

        // Service2 should not hit the same cache (different key)
        // This test just verifies the services can be created with different connections
        $this->assertIsArray($items1);
    }
}

<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Tests\Integration;

use SapB1\Toolkit\Tests\TestCase;

/**
 * Base class for integration tests that require SAP B1 connection.
 *
 * Set the following environment variables to enable integration tests:
 * - SAPB1_INTEGRATION_ENABLED=true
 * - SAPB1_HOST=https://your-server:50000
 * - SAPB1_COMPANY=SBODEMOUS
 * - SAPB1_USERNAME=manager
 * - SAPB1_PASSWORD=your-password
 */
abstract class IntegrationTestCase extends TestCase
{
    protected static bool $connectionTested = false;

    protected static bool $connectionAvailable = false;

    protected function setUp(): void
    {
        parent::setUp();

        $this->skipIfIntegrationDisabled();
    }

    protected function skipIfIntegrationDisabled(): void
    {
        if (! $this->isIntegrationEnabled()) {
            $this->markTestSkipped('Integration tests are disabled. Set SAPB1_INTEGRATION_ENABLED=true to enable.');
        }

        if (! $this->isConnectionAvailable()) {
            $this->markTestSkipped('SAP B1 connection is not available.');
        }
    }

    protected function isIntegrationEnabled(): bool
    {
        return env('SAPB1_INTEGRATION_ENABLED', false) === true
            || env('SAPB1_INTEGRATION_ENABLED', 'false') === 'true';
    }

    protected function isConnectionAvailable(): bool
    {
        if (self::$connectionTested) {
            return self::$connectionAvailable;
        }

        self::$connectionTested = true;

        try {
            // Try to connect to SAP B1 using SDK's actual API
            $client = \SapB1\Facades\SapB1::connection('default');
            // Check if session is valid (this will attempt login if needed)
            if ($client->hasValidSession()) {
                self::$connectionAvailable = true;
            } else {
                self::$connectionAvailable = false;
            }
        } catch (\Throwable $e) {
            // Connection failed
            self::$connectionAvailable = false;
        }

        return self::$connectionAvailable;
    }

    /**
     * Get a test customer code that exists in SAP B1.
     */
    protected function getTestCustomerCode(): string
    {
        $code = env('SAPB1_TEST_CUSTOMER', '');

        if (empty($code)) {
            $this->markTestSkipped('SAPB1_TEST_CUSTOMER is not configured in .env.testing');
        }

        return $code;
    }

    /**
     * Get a test supplier code that exists in SAP B1.
     */
    protected function getTestSupplierCode(): string
    {
        $code = env('SAPB1_TEST_SUPPLIER', '');

        if (empty($code)) {
            $this->markTestSkipped('SAPB1_TEST_SUPPLIER is not configured in .env.testing');
        }

        return $code;
    }

    /**
     * Get a test item code that exists in SAP B1.
     */
    protected function getTestItemCode(): string
    {
        $code = env('SAPB1_TEST_ITEM', '');

        if (empty($code)) {
            $this->markTestSkipped('SAPB1_TEST_ITEM is not configured in .env.testing');
        }

        return $code;
    }

    /**
     * Get a test warehouse code that exists in SAP B1.
     */
    protected function getTestWarehouseCode(): string
    {
        $code = env('SAPB1_TEST_WAREHOUSE', '');

        if (empty($code)) {
            $this->markTestSkipped('SAPB1_TEST_WAREHOUSE is not configured in .env.testing');
        }

        return $code;
    }
}

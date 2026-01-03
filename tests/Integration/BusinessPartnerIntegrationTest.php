<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Tests\Integration;

use SapB1\Toolkit\Actions\BusinessPartner\BusinessPartnerAction;
use SapB1\Toolkit\DTOs\BusinessPartner\BusinessPartnerDto;

class BusinessPartnerIntegrationTest extends IntegrationTestCase
{
    public function test_can_find_customer(): void
    {
        $action = new BusinessPartnerAction;
        $result = $action->find($this->getTestCustomerCode());

        $this->assertInstanceOf(BusinessPartnerDto::class, $result);
        $this->assertEquals($this->getTestCustomerCode(), $result->cardCode);
    }

    public function test_can_get_customer_as_dto(): void
    {
        $action = new BusinessPartnerAction;
        $dto = $action->find($this->getTestCustomerCode());

        $this->assertInstanceOf(BusinessPartnerDto::class, $dto);
        $this->assertEquals($this->getTestCustomerCode(), $dto->cardCode);
    }

    public function test_can_list_customers(): void
    {
        $action = new BusinessPartnerAction;
        $result = $action->getCustomers();

        $this->assertIsArray($result);
        // May be empty if no customers exist
    }

    public function test_can_list_suppliers(): void
    {
        $action = new BusinessPartnerAction;
        $result = $action->getVendors();

        $this->assertIsArray($result);
    }

    public function test_can_search_business_partners(): void
    {
        $action = new BusinessPartnerAction;
        $result = $action->search('');

        $this->assertIsArray($result);
    }
}

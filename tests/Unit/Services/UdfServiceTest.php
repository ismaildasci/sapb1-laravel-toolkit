<?php

declare(strict_types=1);

use SapB1\Toolkit\Exceptions\UdfException;
use SapB1\Toolkit\Services\UdfService;

it('can be instantiated', function () {
    $service = new UdfService;

    expect($service)->toBeInstanceOf(UdfService::class);
});

it('can set connection', function () {
    $service = new UdfService;
    $result = $service->connection('secondary');

    expect($result)->toBeInstanceOf(UdfService::class);
});

// ==================== ENTITY TABLE MAPPING ====================

it('resolves Orders entity to ORDR table', function () {
    $service = new UdfService;

    expect($service->resolveTableName('Orders'))->toBe('ORDR');
});

it('resolves Invoices entity to OINV table', function () {
    $service = new UdfService;

    expect($service->resolveTableName('Invoices'))->toBe('OINV');
});

it('resolves BusinessPartners entity to OCRD table', function () {
    $service = new UdfService;

    expect($service->resolveTableName('BusinessPartners'))->toBe('OCRD');
});

it('resolves Items entity to OITM table', function () {
    $service = new UdfService;

    expect($service->resolveTableName('Items'))->toBe('OITM');
});

it('resolves DeliveryNotes entity to ODLN table', function () {
    $service = new UdfService;

    expect($service->resolveTableName('DeliveryNotes'))->toBe('ODLN');
});

it('resolves PurchaseOrders entity to OPOR table', function () {
    $service = new UdfService;

    expect($service->resolveTableName('PurchaseOrders'))->toBe('OPOR');
});

it('resolves JournalEntries entity to OJDT table', function () {
    $service = new UdfService;

    expect($service->resolveTableName('JournalEntries'))->toBe('OJDT');
});

it('resolves ProductionOrders entity to OWOR table', function () {
    $service = new UdfService;

    expect($service->resolveTableName('ProductionOrders'))->toBe('OWOR');
});

it('resolves ServiceCalls entity to OSCL table', function () {
    $service = new UdfService;

    expect($service->resolveTableName('ServiceCalls'))->toBe('OSCL');
});

it('resolves EmployeesInfo entity to OHEM table', function () {
    $service = new UdfService;

    expect($service->resolveTableName('EmployeesInfo'))->toBe('OHEM');
});

it('throws exception for unmapped entity', function () {
    $service = new UdfService;

    $service->resolveTableName('UnknownEntity');
})->throws(UdfException::class, "Entity 'UnknownEntity' is not mapped to a SAP table");

// ==================== LINE TABLE MAPPING ====================

it('resolves Orders line table to RDR1', function () {
    $service = new UdfService;

    expect($service->resolveLineTableName('Orders'))->toBe('RDR1');
});

it('resolves Invoices line table to INV1', function () {
    $service = new UdfService;

    expect($service->resolveLineTableName('Invoices'))->toBe('INV1');
});

it('resolves DeliveryNotes line table to DLN1', function () {
    $service = new UdfService;

    expect($service->resolveLineTableName('DeliveryNotes'))->toBe('DLN1');
});

it('resolves PurchaseOrders line table to POR1', function () {
    $service = new UdfService;

    expect($service->resolveLineTableName('PurchaseOrders'))->toBe('POR1');
});

it('throws exception for unmapped line table', function () {
    $service = new UdfService;

    $service->resolveLineTableName('BusinessPartners');
})->throws(UdfException::class, "Entity 'BusinessPartners' does not have a mapped line table");

// ==================== ENTITY MAPPING UTILITIES ====================

it('checks if entity is mapped', function () {
    $service = new UdfService;

    expect($service->isEntityMapped('Orders'))->toBeTrue();
    expect($service->isEntityMapped('Invoices'))->toBeTrue();
    expect($service->isEntityMapped('UnknownEntity'))->toBeFalse();
});

it('resolves entity name from table name', function () {
    $service = new UdfService;

    expect($service->resolveEntityName('ORDR'))->toBe('Orders');
    expect($service->resolveEntityName('OINV'))->toBe('Invoices');
    expect($service->resolveEntityName('OCRD'))->toBe('BusinessPartners');
    expect($service->resolveEntityName('UNKNOWN'))->toBeNull();
});

it('returns all mapped entities', function () {
    $service = new UdfService;
    $entities = $service->getMappedEntities();

    expect($entities)->toBeArray();
    expect($entities)->toHaveKey('Orders');
    expect($entities)->toHaveKey('Invoices');
    expect($entities)->toHaveKey('BusinessPartners');
    expect($entities)->toHaveKey('Items');
    expect($entities['Orders'])->toBe('ORDR');
});

it('returns all mapped line tables', function () {
    $service = new UdfService;
    $tables = $service->getMappedLineTables();

    expect($tables)->toBeArray();
    expect($tables)->toHaveKey('Orders');
    expect($tables)->toHaveKey('Invoices');
    expect($tables['Orders'])->toBe('RDR1');
});

// ==================== TYPE HELPERS ====================

it('returns type labels', function () {
    $service = new UdfService;

    expect($service->getTypeLabel('db_Alpha'))->toBe('Alphanumeric');
    expect($service->getTypeLabel('db_Memo'))->toBe('Memo');
    expect($service->getTypeLabel('db_Numeric'))->toBe('Numeric');
    expect($service->getTypeLabel('db_Date'))->toBe('Date');
    expect($service->getTypeLabel('db_Float'))->toBe('Decimal');
    expect($service->getTypeLabel('unknown'))->toBe('unknown');
});

it('returns subtype labels', function () {
    $service = new UdfService;

    expect($service->getSubTypeLabel(null))->toBe('None');
    expect($service->getSubTypeLabel('st_None'))->toBe('None');
    expect($service->getSubTypeLabel('st_Address'))->toBe('Address');
    expect($service->getSubTypeLabel('st_Phone'))->toBe('Phone');
    expect($service->getSubTypeLabel('st_Time'))->toBe('Time');
    expect($service->getSubTypeLabel('st_Rate'))->toBe('Rate');
    expect($service->getSubTypeLabel('st_Sum'))->toBe('Sum');
    expect($service->getSubTypeLabel('st_Price'))->toBe('Price');
    expect($service->getSubTypeLabel('st_Quantity'))->toBe('Quantity');
    expect($service->getSubTypeLabel('st_Percentage'))->toBe('Percentage');
    expect($service->getSubTypeLabel('st_Measurement'))->toBe('Measurement');
    expect($service->getSubTypeLabel('st_Link'))->toBe('Link');
    expect($service->getSubTypeLabel('st_Image'))->toBe('Image');
    expect($service->getSubTypeLabel('unknown'))->toBe('unknown');
});

// ==================== QUERY METHODS ====================

it('has getFieldsForEntity method', function () {
    $service = new UdfService;

    expect(method_exists($service, 'getFieldsForEntity'))->toBeTrue();
});

it('has getLineFieldsForEntity method', function () {
    $service = new UdfService;

    expect(method_exists($service, 'getLineFieldsForEntity'))->toBeTrue();
});

it('has getField method', function () {
    $service = new UdfService;

    expect(method_exists($service, 'getField'))->toBeTrue();
});

it('has hasField method', function () {
    $service = new UdfService;

    expect(method_exists($service, 'hasField'))->toBeTrue();
});

it('has getFieldNames method', function () {
    $service = new UdfService;

    expect(method_exists($service, 'getFieldNames'))->toBeTrue();
});

it('has getFieldsForTable method', function () {
    $service = new UdfService;

    expect(method_exists($service, 'getFieldsForTable'))->toBeTrue();
});

it('has getFieldByTableAndName method', function () {
    $service = new UdfService;

    expect(method_exists($service, 'getFieldByTableAndName'))->toBeTrue();
});

it('has getFieldById method', function () {
    $service = new UdfService;

    expect(method_exists($service, 'getFieldById'))->toBeTrue();
});

it('has listAll method', function () {
    $service = new UdfService;

    expect(method_exists($service, 'listAll'))->toBeTrue();
});

it('has listGroupedByTable method', function () {
    $service = new UdfService;

    expect(method_exists($service, 'listGroupedByTable'))->toBeTrue();
});

it('has countFieldsForEntity method', function () {
    $service = new UdfService;

    expect(method_exists($service, 'countFieldsForEntity'))->toBeTrue();
});

it('has countFieldsForTable method', function () {
    $service = new UdfService;

    expect(method_exists($service, 'countFieldsForTable'))->toBeTrue();
});

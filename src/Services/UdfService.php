<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Services;

use Illuminate\Support\Collection;
use SapB1\Toolkit\Exceptions\UdfException;

/**
 * Service for reading User Defined Field (UDF) metadata from SAP B1.
 *
 * Provides read-only access to UDF definitions through the UserFieldsMD endpoint.
 * UDF values are accessed directly on documents via U_* prefixed fields.
 *
 * Service Layer Endpoint: UserFieldsMD
 * - GET UserFieldsMD - List all UDF definitions
 * - GET UserFieldsMD(TableName='ORDR',FieldID=0) - Get specific UDF
 *
 * @example
 * ```php
 * $service = app(UdfService::class);
 *
 * // Get all UDFs for Orders entity
 * $fields = $service->getFieldsForEntity('Orders');
 *
 * // Check if a UDF exists
 * if ($service->hasField('Orders', 'CustomField')) {
 *     $field = $service->getField('Orders', 'CustomField');
 * }
 *
 * // Get UDFs for a specific SAP table
 * $fields = $service->getFieldsForTable('ORDR');
 * ```
 */
final class UdfService extends BaseService
{
    private const ENDPOINT = 'UserFieldsMD';

    /**
     * Entity to SAP table name mapping.
     *
     * Maps Service Layer entity names to internal SAP B1 table names
     * used in UserFieldsMD queries.
     *
     * @var array<string, string>
     */
    private const ENTITY_TABLE_MAP = [
        // Sales Documents
        'Quotations' => 'OQUT',
        'Orders' => 'ORDR',
        'DeliveryNotes' => 'ODLN',
        'Returns' => 'ORDN',
        'Invoices' => 'OINV',
        'CreditNotes' => 'ORIN',
        'DownPayments' => 'ODPI',
        'CorrectionInvoice' => 'OCIN',
        'CorrectionInvoiceReversal' => 'OCIR',

        // Purchase Documents
        'PurchaseQuotations' => 'OPQT',
        'PurchaseOrders' => 'OPOR',
        'PurchaseDeliveryNotes' => 'OPDN',
        'PurchaseReturns' => 'ORPD',
        'PurchaseInvoices' => 'OPCH',
        'PurchaseCreditNotes' => 'ORPC',
        'PurchaseDownPayments' => 'ODPO',

        // Inventory
        'Items' => 'OITM',
        'Warehouses' => 'OWHS',
        'StockTransfers' => 'OWTR',
        'StockTransferRequests' => 'OWTQ',
        'InventoryGenEntries' => 'OIGN',
        'InventoryGenExits' => 'OIGE',
        'InventoryCountings' => 'OINC',
        'InventoryPostings' => 'OIQR',
        'InventoryOpeningBalances' => 'OIBQ',

        // Business Partners
        'BusinessPartners' => 'OCRD',
        'Activities' => 'OCLG',
        'SalesOpportunities' => 'OOPR',
        'Campaigns' => 'OMRC',

        // Finance
        'JournalEntries' => 'OJDT',
        'IncomingPayments' => 'ORCT',
        'VendorPayments' => 'OVPM',
        'ChartOfAccounts' => 'OACT',
        'CostCenters' => 'OPRC',
        'Projects' => 'OPRJ',
        'BudgetScenarios' => 'OBGS',
        'Budgets' => 'OBGT',

        // Production
        'ProductionOrders' => 'OWOR',
        'ProductTrees' => 'OITT',
        'Resources' => 'ORSC',
        'ResourceCapacities' => 'ORCP',

        // Service
        'ServiceCalls' => 'OSCL',
        'ServiceContracts' => 'OCTR',

        // HR
        'EmployeesInfo' => 'OHEM',

        // Admin
        'Users' => 'OUSR',
    ];

    /**
     * Document line table mapping.
     *
     * @var array<string, string>
     */
    private const LINE_TABLE_MAP = [
        'Quotations' => 'QUT1',
        'Orders' => 'RDR1',
        'DeliveryNotes' => 'DLN1',
        'Returns' => 'RDN1',
        'Invoices' => 'INV1',
        'CreditNotes' => 'RIN1',
        'DownPayments' => 'DPI1',
        'PurchaseQuotations' => 'PQT1',
        'PurchaseOrders' => 'POR1',
        'PurchaseDeliveryNotes' => 'PDN1',
        'PurchaseReturns' => 'RPD1',
        'PurchaseInvoices' => 'PCH1',
        'PurchaseCreditNotes' => 'RPC1',
        'PurchaseDownPayments' => 'DPO1',
        'StockTransfers' => 'WTR1',
        'InventoryGenEntries' => 'IGN1',
        'InventoryGenExits' => 'IGE1',
        'JournalEntries' => 'JDT1',
        'ProductionOrders' => 'WOR1',
    ];

    // ==================== ENTITY-LEVEL UDF QUERIES ====================

    /**
     * Get all UDF definitions for a Service Layer entity.
     *
     * @param  string  $entity  The entity name (e.g., 'Orders', 'BusinessPartners')
     * @return Collection<int, array<string, mixed>>
     *
     * @throws UdfException If entity is not mapped
     */
    public function getFieldsForEntity(string $entity): Collection
    {
        $tableName = $this->resolveTableName($entity);

        return $this->getFieldsForTable($tableName);
    }

    /**
     * Get all UDF definitions for document lines of an entity.
     *
     * @param  string  $entity  The entity name (e.g., 'Orders', 'Invoices')
     * @return Collection<int, array<string, mixed>>
     *
     * @throws UdfException If entity line table is not mapped
     */
    public function getLineFieldsForEntity(string $entity): Collection
    {
        $tableName = $this->resolveLineTableName($entity);

        return $this->getFieldsForTable($tableName);
    }

    /**
     * Get a specific UDF definition for an entity.
     *
     * @param  string  $entity  The entity name
     * @param  string  $fieldName  The UDF name (without U_ prefix)
     * @return array<string, mixed>|null
     *
     * @throws UdfException If entity is not mapped
     */
    public function getField(string $entity, string $fieldName): ?array
    {
        $tableName = $this->resolveTableName($entity);

        return $this->getFieldByTableAndName($tableName, $fieldName);
    }

    /**
     * Check if a UDF exists for an entity.
     *
     * @param  string  $entity  The entity name
     * @param  string  $fieldName  The UDF name (without U_ prefix)
     *
     * @throws UdfException If entity is not mapped
     */
    public function hasField(string $entity, string $fieldName): bool
    {
        return $this->getField($entity, $fieldName) !== null;
    }

    /**
     * Get UDF names for an entity.
     *
     * @param  string  $entity  The entity name
     * @return Collection<int, string>
     *
     * @throws UdfException If entity is not mapped
     */
    public function getFieldNames(string $entity): Collection
    {
        return $this->getFieldsForEntity($entity)->pluck('Name');
    }

    // ==================== TABLE-LEVEL UDF QUERIES ====================

    /**
     * Get all UDF definitions for a SAP table.
     *
     * @param  string  $tableName  The SAP table name (e.g., 'ORDR', '@UDT01')
     * @return Collection<int, array<string, mixed>>
     */
    public function getFieldsForTable(string $tableName): Collection
    {
        $response = $this->client()
            ->service(self::ENDPOINT)
            ->queryBuilder()
            ->filter("TableName eq '{$tableName}'")
            ->orderBy('FieldID', 'asc')
            ->get();

        return collect($response['value'] ?? []);
    }

    /**
     * Get a specific UDF by table name and field name.
     *
     * @param  string  $tableName  The SAP table name
     * @param  string  $fieldName  The field name (without U_ prefix)
     * @return array<string, mixed>|null
     */
    public function getFieldByTableAndName(string $tableName, string $fieldName): ?array
    {
        $response = $this->client()
            ->service(self::ENDPOINT)
            ->queryBuilder()
            ->filter("TableName eq '{$tableName}' and Name eq '{$fieldName}'")
            ->top(1)
            ->get();

        $fields = $response['value'] ?? [];

        return $fields[0] ?? null;
    }

    /**
     * Get a UDF by its composite key.
     *
     * @param  string  $tableName  The SAP table name
     * @param  int  $fieldId  The field ID
     * @return array<string, mixed>|null
     */
    public function getFieldById(string $tableName, int $fieldId): ?array
    {
        try {
            $response = $this->client()
                ->service(self::ENDPOINT)
                ->find("TableName='{$tableName}',FieldID={$fieldId}");

            return $response ?? null;
        } catch (\Exception) {
            return null;
        }
    }

    // ==================== LIST ALL UDFs ====================

    /**
     * Get all UDF definitions in the system.
     *
     * @param  int|null  $top  Maximum number of results
     * @param  int|null  $skip  Number of results to skip
     * @return Collection<int, array<string, mixed>>
     */
    public function listAll(?int $top = null, ?int $skip = null): Collection
    {
        $query = $this->client()
            ->service(self::ENDPOINT)
            ->queryBuilder()
            ->orderBy('TableName', 'asc');

        if ($top !== null) {
            $query->top($top);
        }

        if ($skip !== null) {
            $query->skip($skip);
        }

        $response = $query->get();

        return collect($response['value'] ?? []);
    }

    /**
     * Get UDFs grouped by table name.
     *
     * @return Collection<string, Collection<int, array<string, mixed>>>
     */
    public function listGroupedByTable(): Collection
    {
        return $this->listAll()->groupBy('TableName');
    }

    /**
     * Get count of UDFs for an entity.
     *
     * @param  string  $entity  The entity name
     *
     * @throws UdfException If entity is not mapped
     */
    public function countFieldsForEntity(string $entity): int
    {
        $tableName = $this->resolveTableName($entity);

        return $this->countFieldsForTable($tableName);
    }

    /**
     * Get count of UDFs for a table.
     *
     * @param  string  $tableName  The SAP table name
     */
    public function countFieldsForTable(string $tableName): int
    {
        return $this->getFieldsForTable($tableName)->count();
    }

    // ==================== ENTITY MAPPING UTILITIES ====================

    /**
     * Get the SAP table name for an entity.
     *
     * @param  string  $entity  The entity name
     *
     * @throws UdfException If entity is not mapped
     */
    public function resolveTableName(string $entity): string
    {
        if (! isset(self::ENTITY_TABLE_MAP[$entity])) {
            throw UdfException::entityNotMapped($entity);
        }

        return self::ENTITY_TABLE_MAP[$entity];
    }

    /**
     * Get the SAP line table name for an entity.
     *
     * @param  string  $entity  The entity name
     *
     * @throws UdfException If entity line table is not mapped
     */
    public function resolveLineTableName(string $entity): string
    {
        if (! isset(self::LINE_TABLE_MAP[$entity])) {
            throw UdfException::lineTableNotMapped($entity);
        }

        return self::LINE_TABLE_MAP[$entity];
    }

    /**
     * Get the entity name from a SAP table name.
     *
     * @param  string  $tableName  The SAP table name
     */
    public function resolveEntityName(string $tableName): ?string
    {
        $flipped = array_flip(self::ENTITY_TABLE_MAP);

        return $flipped[$tableName] ?? null;
    }

    /**
     * Check if an entity is mapped.
     *
     * @param  string  $entity  The entity name
     */
    public function isEntityMapped(string $entity): bool
    {
        return isset(self::ENTITY_TABLE_MAP[$entity]);
    }

    /**
     * Get all mapped entities.
     *
     * @return array<string, string>
     */
    public function getMappedEntities(): array
    {
        return self::ENTITY_TABLE_MAP;
    }

    /**
     * Get all mapped line tables.
     *
     * @return array<string, string>
     */
    public function getMappedLineTables(): array
    {
        return self::LINE_TABLE_MAP;
    }

    // ==================== UDF FIELD TYPE HELPERS ====================

    /**
     * Get UDF type label.
     *
     * @param  string  $type  The UDF type code from SAP
     */
    public function getTypeLabel(string $type): string
    {
        return match ($type) {
            'db_Alpha' => 'Alphanumeric',
            'db_Memo' => 'Memo',
            'db_Numeric' => 'Numeric',
            'db_Date' => 'Date',
            'db_Float' => 'Decimal',
            default => $type,
        };
    }

    /**
     * Get UDF subtype label.
     *
     * @param  string|null  $subType  The UDF subtype code from SAP
     */
    public function getSubTypeLabel(?string $subType): string
    {
        if ($subType === null) {
            return 'None';
        }

        return match ($subType) {
            'st_None' => 'None',
            'st_Address' => 'Address',
            'st_Phone' => 'Phone',
            'st_Time' => 'Time',
            'st_Rate' => 'Rate',
            'st_Sum' => 'Sum',
            'st_Price' => 'Price',
            'st_Quantity' => 'Quantity',
            'st_Percentage' => 'Percentage',
            'st_Measurement' => 'Measurement',
            'st_Link' => 'Link',
            'st_Image' => 'Image',
            default => $subType,
        };
    }
}

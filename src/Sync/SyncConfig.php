<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Sync;

use Closure;

/**
 * Configuration for syncing a SAP B1 entity to local database.
 */
final class SyncConfig
{
    /**
     * Create a new SyncConfig instance.
     *
     * @param  string  $entity  SAP B1 entity name (e.g., 'Items', 'Orders')
     * @param  string  $table  Local database table name
     * @param  string  $primaryKey  Primary key field name in SAP
     * @param  array<string>  $columns  Columns to sync
     * @param  string|null  $updateDateField  Field used for incremental sync
     * @param  int  $batchSize  Number of records per batch
     * @param  bool  $syncLines  Whether to sync document lines
     * @param  string|null  $linesTable  Table name for document lines
     * @param  array<string>|null  $lineColumns  Columns for document lines
     * @param  Closure|null  $transformer  Custom data transformer
     * @param  string|null  $filter  Additional OData filter
     * @param  bool  $trackDeletes  Whether to track deleted records
     */
    public function __construct(
        public readonly string $entity,
        public readonly string $table,
        public readonly string $primaryKey,
        public readonly array $columns = [],
        public readonly ?string $updateDateField = 'UpdateDate',
        public readonly int $batchSize = 5000,
        public readonly bool $syncLines = false,
        public readonly ?string $linesTable = null,
        public readonly ?array $lineColumns = null,
        public readonly ?Closure $transformer = null,
        public readonly ?string $filter = null,
        public readonly bool $trackDeletes = true,
    ) {}

    /**
     * Create a config for Items entity.
     */
    public static function items(): self
    {
        return new self(
            entity: 'Items',
            table: 'sap_items',
            primaryKey: 'ItemCode',
            columns: [
                'ItemCode', 'ItemName', 'ItemType', 'ItemsGroupCode',
                'QuantityOnStock', 'QuantityOrderedFromVendors', 'QuantityOrderedByCustomers',
                'AvgStdPrice', 'DefaultWarehouse',
                'SalesItem', 'PurchaseItem', 'InventoryItem', 'Valid', 'Frozen',
            ],
        );
    }

    /**
     * Create a config for BusinessPartners entity.
     */
    public static function businessPartners(): self
    {
        return new self(
            entity: 'BusinessPartners',
            table: 'sap_business_partners',
            primaryKey: 'CardCode',
            columns: [
                'CardCode', 'CardName', 'CardType', 'GroupCode',
                'Phone1', 'Phone2', 'Fax', 'EmailAddress',
                'ContactPerson', 'Currency', 'Valid', 'Frozen',
                'Balance', 'OrdersBalance', 'DeliveryNotesBalance',
            ],
        );
    }

    /**
     * Create a config for Orders entity.
     */
    public static function orders(): self
    {
        return new self(
            entity: 'Orders',
            table: 'sap_orders',
            primaryKey: 'DocEntry',
            columns: [
                'DocEntry', 'DocNum', 'CardCode', 'CardName',
                'DocDate', 'DocDueDate', 'TaxDate',
                'DocTotal', 'VatSum', 'DiscountPercent', 'DocCurrency',
                'DocStatus', 'Cancelled', 'NumAtCard', 'Comments',
            ],
            syncLines: true,
            linesTable: 'sap_order_lines',
            lineColumns: [
                'DocEntry', 'LineNum', 'ItemCode', 'ItemDescription',
                'Quantity', 'Price', 'LineTotal', 'WarehouseCode', 'LineStatus',
            ],
        );
    }

    /**
     * Create a config for Invoices entity.
     */
    public static function invoices(): self
    {
        return new self(
            entity: 'Invoices',
            table: 'sap_invoices',
            primaryKey: 'DocEntry',
            columns: [
                'DocEntry', 'DocNum', 'CardCode', 'CardName',
                'DocDate', 'DocDueDate', 'TaxDate',
                'DocTotal', 'VatSum', 'DiscountPercent', 'DocCurrency',
                'DocStatus', 'Cancelled', 'NumAtCard', 'Comments',
            ],
            syncLines: true,
            linesTable: 'sap_invoice_lines',
            lineColumns: [
                'DocEntry', 'LineNum', 'ItemCode', 'ItemDescription',
                'Quantity', 'Price', 'LineTotal', 'WarehouseCode', 'LineStatus',
            ],
        );
    }

    /**
     * Create a config for DeliveryNotes entity.
     */
    public static function deliveryNotes(): self
    {
        return new self(
            entity: 'DeliveryNotes',
            table: 'sap_delivery_notes',
            primaryKey: 'DocEntry',
            columns: [
                'DocEntry', 'DocNum', 'CardCode', 'CardName',
                'DocDate', 'DocDueDate', 'TaxDate',
                'DocTotal', 'VatSum', 'DiscountPercent', 'DocCurrency',
                'DocStatus', 'Cancelled', 'NumAtCard', 'Comments',
            ],
            syncLines: true,
            linesTable: 'sap_delivery_note_lines',
            lineColumns: [
                'DocEntry', 'LineNum', 'ItemCode', 'ItemDescription',
                'Quantity', 'Price', 'LineTotal', 'WarehouseCode', 'LineStatus',
            ],
        );
    }

    /**
     * Create a config for Quotations entity.
     */
    public static function quotations(): self
    {
        return new self(
            entity: 'Quotations',
            table: 'sap_quotations',
            primaryKey: 'DocEntry',
            columns: [
                'DocEntry', 'DocNum', 'CardCode', 'CardName',
                'DocDate', 'DocDueDate', 'TaxDate',
                'DocTotal', 'VatSum', 'DiscountPercent', 'DocCurrency',
                'DocStatus', 'Cancelled', 'NumAtCard', 'Comments',
            ],
            syncLines: true,
            linesTable: 'sap_quotation_lines',
            lineColumns: [
                'DocEntry', 'LineNum', 'ItemCode', 'ItemDescription',
                'Quantity', 'Price', 'LineTotal', 'WarehouseCode', 'LineStatus',
            ],
        );
    }

    /**
     * Create a config for CreditNotes entity.
     */
    public static function creditNotes(): self
    {
        return new self(
            entity: 'CreditNotes',
            table: 'sap_credit_notes',
            primaryKey: 'DocEntry',
            columns: [
                'DocEntry', 'DocNum', 'CardCode', 'CardName',
                'DocDate', 'DocDueDate', 'TaxDate',
                'DocTotal', 'VatSum', 'DiscountPercent', 'DocCurrency',
                'DocStatus', 'Cancelled', 'NumAtCard', 'Comments',
            ],
            syncLines: true,
            linesTable: 'sap_credit_note_lines',
            lineColumns: [
                'DocEntry', 'LineNum', 'ItemCode', 'ItemDescription',
                'Quantity', 'Price', 'LineTotal', 'WarehouseCode', 'LineStatus',
            ],
        );
    }

    /**
     * Create a config for PurchaseOrders entity.
     */
    public static function purchaseOrders(): self
    {
        return new self(
            entity: 'PurchaseOrders',
            table: 'sap_purchase_orders',
            primaryKey: 'DocEntry',
            columns: [
                'DocEntry', 'DocNum', 'CardCode', 'CardName',
                'DocDate', 'DocDueDate', 'TaxDate',
                'DocTotal', 'VatSum', 'DiscountPercent', 'DocCurrency',
                'DocStatus', 'Cancelled', 'NumAtCard', 'Comments',
            ],
            syncLines: true,
            linesTable: 'sap_purchase_order_lines',
            lineColumns: [
                'DocEntry', 'LineNum', 'ItemCode', 'ItemDescription',
                'Quantity', 'Price', 'LineTotal', 'WarehouseCode', 'LineStatus',
            ],
        );
    }

    /**
     * Create a config for PurchaseInvoices entity.
     */
    public static function purchaseInvoices(): self
    {
        return new self(
            entity: 'PurchaseInvoices',
            table: 'sap_purchase_invoices',
            primaryKey: 'DocEntry',
            columns: [
                'DocEntry', 'DocNum', 'CardCode', 'CardName',
                'DocDate', 'DocDueDate', 'TaxDate',
                'DocTotal', 'VatSum', 'DiscountPercent', 'DocCurrency',
                'DocStatus', 'Cancelled', 'NumAtCard', 'Comments',
            ],
            syncLines: true,
            linesTable: 'sap_purchase_invoice_lines',
            lineColumns: [
                'DocEntry', 'LineNum', 'ItemCode', 'ItemDescription',
                'Quantity', 'Price', 'LineTotal', 'WarehouseCode', 'LineStatus',
            ],
        );
    }

    /**
     * Create a config for GoodsReceiptPO entity.
     */
    public static function goodsReceiptPO(): self
    {
        return new self(
            entity: 'PurchaseDeliveryNotes',
            table: 'sap_goods_receipt_po',
            primaryKey: 'DocEntry',
            columns: [
                'DocEntry', 'DocNum', 'CardCode', 'CardName',
                'DocDate', 'DocDueDate', 'TaxDate',
                'DocTotal', 'VatSum', 'DiscountPercent', 'DocCurrency',
                'DocStatus', 'Cancelled', 'NumAtCard', 'Comments',
            ],
            syncLines: true,
            linesTable: 'sap_goods_receipt_po_lines',
            lineColumns: [
                'DocEntry', 'LineNum', 'ItemCode', 'ItemDescription',
                'Quantity', 'Price', 'LineTotal', 'WarehouseCode', 'LineStatus',
            ],
        );
    }

    /**
     * Get all predefined entity configurations.
     *
     * @return array<string, self>
     */
    public static function all(): array
    {
        return [
            'Items' => self::items(),
            'BusinessPartners' => self::businessPartners(),
            'Orders' => self::orders(),
            'Invoices' => self::invoices(),
            'DeliveryNotes' => self::deliveryNotes(),
            'Quotations' => self::quotations(),
            'CreditNotes' => self::creditNotes(),
            'PurchaseOrders' => self::purchaseOrders(),
            'PurchaseInvoices' => self::purchaseInvoices(),
            'GoodsReceiptPO' => self::goodsReceiptPO(),
        ];
    }

    /**
     * Get available entity names.
     *
     * @return array<string>
     */
    public static function availableEntities(): array
    {
        return array_keys(self::all());
    }

    /**
     * Check if an entity is available.
     */
    public static function isAvailable(string $entity): bool
    {
        return isset(self::all()[$entity]);
    }

    /**
     * Get config for an entity.
     */
    public static function for(string $entity): ?self
    {
        return self::all()[$entity] ?? null;
    }

    /**
     * Create a copy with modified properties.
     *
     * @param  array<string, mixed>  $properties
     */
    public function with(array $properties): self
    {
        return new self(
            entity: $properties['entity'] ?? $this->entity,
            table: $properties['table'] ?? $this->table,
            primaryKey: $properties['primaryKey'] ?? $this->primaryKey,
            columns: $properties['columns'] ?? $this->columns,
            updateDateField: $properties['updateDateField'] ?? $this->updateDateField,
            batchSize: $properties['batchSize'] ?? $this->batchSize,
            syncLines: $properties['syncLines'] ?? $this->syncLines,
            linesTable: $properties['linesTable'] ?? $this->linesTable,
            lineColumns: $properties['lineColumns'] ?? $this->lineColumns,
            transformer: $properties['transformer'] ?? $this->transformer,
            filter: $properties['filter'] ?? $this->filter,
            trackDeletes: $properties['trackDeletes'] ?? $this->trackDeletes,
        );
    }

    /**
     * Convert to array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'entity' => $this->entity,
            'table' => $this->table,
            'primaryKey' => $this->primaryKey,
            'columns' => $this->columns,
            'updateDateField' => $this->updateDateField,
            'batchSize' => $this->batchSize,
            'syncLines' => $this->syncLines,
            'linesTable' => $this->linesTable,
            'lineColumns' => $this->lineColumns,
            'filter' => $this->filter,
            'trackDeletes' => $this->trackDeletes,
        ];
    }
}

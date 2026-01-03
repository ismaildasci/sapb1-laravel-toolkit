# Changelog

All notable changes to `laravel-toolkit` will be documented in this file.

## v1.0.0-beta.1 - 2025-01-03

### Added

#### Core Infrastructure
- Base contracts: `ActionInterface`, `BuilderInterface`, `DtoInterface`, `ServiceInterface`
- Base classes: `BaseAction`, `BaseDto`, `BaseBuilder`, `DocumentAction`
- Common traits: `HasDocumentLines`, `HasApproval`, `HasTaxes`, `Cancellable`, `Closable`

#### Enums (15 enums)
- Document enums: `DocumentStatus`, `PrintStatus`, `DocumentType`
- Business Partner enums: `CardType`, `GroupType`, `PaymentTermsType`, `ShippingType`
- Inventory enums: `ItemType`, `ItemClass`, `ValuationMethod`
- Finance enums: `AccountType`, `PaymentMethod`
- Common enums: `BoYesNo`, `ApprovalStatus`, `ProductionOrderStatus`

#### DTOs (27 DTOs)
- Base DTOs: `AddressDto`, `DocumentDto`, `DocumentLineDto`
- Sales DTOs: `OrderDto`, `QuotationDto`, `DeliveryNoteDto`, `InvoiceDto`, `CreditNoteDto`, `ReturnDto`
- Purchase DTOs: `PurchaseOrderDto`, `GoodsReceiptDto`, `PurchaseInvoiceDto`, `PurchaseReturnDto`
- Inventory DTOs: `ItemDto`, `WarehouseDto`, `StockTransferDto`, `StockTransferLineDto`, `BatchDto`
- Business Partner DTOs: `BusinessPartnerDto`, `ContactPersonDto`, `ActivityDto`
- Finance DTOs: `JournalEntryDto`, `JournalEntryLineDto`, `PaymentDto`, `PaymentInvoiceDto`, `ChartOfAccountDto`
- Response DTOs: `ApiResponseDto`, `PaginatedResponseDto`, `BatchResponseDto`, `BatchItemResponseDto`

#### Builders (19 builders)
- Base builders: `DocumentBuilder`, `DocumentLineBuilder`
- Sales builders: `OrderBuilder`, `QuotationBuilder`, `DeliveryBuilder`, `InvoiceBuilder`, `CreditNoteBuilder`, `ReturnBuilder`
- Purchase builders: `PurchaseOrderBuilder`, `GoodsReceiptBuilder`, `PurchaseInvoiceBuilder`, `PurchaseReturnBuilder`
- Inventory builders: `ItemBuilder`, `WarehouseBuilder`, `StockTransferBuilder`
- Finance builders: `PaymentBuilder`, `JournalEntryBuilder`
- Business Partner builders: `BusinessPartnerBuilder`, `ActivityBuilder`

#### Actions (17 actions)
- Sales actions: `OrderAction`, `QuotationAction`, `DeliveryAction`, `InvoiceAction`, `CreditNoteAction`, `ReturnAction`
- Purchase actions: `PurchaseOrderAction`, `GoodsReceiptAction`, `PurchaseInvoiceAction`, `PurchaseReturnAction`
- Inventory actions: `ItemAction`, `WarehouseAction`, `StockTransferAction`
- Finance actions: `PaymentAction`, `JournalEntryAction`
- Business Partner actions: `BusinessPartnerAction`, `ActivityAction`

#### Services (7 services)
- `BaseService` - Base service with connection management
- `DocumentFlowService` - Document conversion (order to invoice, order to delivery, etc.)
- `PaymentService` - Incoming/outgoing payment management
- `InventoryService` - Stock transfers, batch operations, stock queries
- `ReportingService` - Sales/purchase summaries, aging reports, top customers/items
- `ApprovalService` - Approval workflow management
- `SyncService` - Full and incremental data synchronization

#### Events (7 events)
- `DocumentCreated` - Fired when a document is created
- `DocumentUpdated` - Fired when a document is updated
- `DocumentClosed` - Fired when a document is closed
- `DocumentCancelled` - Fired when a document is cancelled
- `PaymentReceived` - Fired when a payment is received
- `ApprovalRequested` - Fired when approval is requested
- `ApprovalCompleted` - Fired when approval is completed

#### Exceptions (8 exceptions)
- `SapB1Exception` - Base exception class
- `DocumentNotFoundException` - Document not found
- `ValidationException` - Validation errors
- `ConnectionException` - Connection errors
- `AuthenticationException` - Authentication errors
- `DocumentClosedException` - Document is closed
- `InsufficientStockException` - Insufficient stock
- `ApprovalRequiredException` - Approval required

#### Validation Rules (5 rules)
- `CardCodeRule` - Validates business partner codes
- `ItemCodeRule` - Validates item codes (with sales/purchase item checks)
- `DocEntryRule` - Validates document entry numbers
- `WarehouseCodeRule` - Validates warehouse codes
- `AccountCodeRule` - Validates chart of account codes

#### Attribute Casts (5 casts)
- `SapDateCast` - Converts SAP B1 date formats
- `SapBooleanCast` - Converts SAP B1 tYES/tNO to boolean
- `MoneyAmountCast` - Handles monetary amounts with precision
- `DocumentTypeCast` - Casts to DocumentType enum
- `CardTypeCast` - Casts to CardType enum

#### Artisan Commands (4 commands)
- `sapb1:test` - Test SAP B1 connection
- `sapb1:sync` - Sync data from SAP B1 (full or incremental)
- `sapb1:cache` - Cache management (warm/clear)
- `sapb1:report` - Generate reports (sales, purchases, aging, top-customers, top-items)

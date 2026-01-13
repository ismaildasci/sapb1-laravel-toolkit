# Changelog

All notable changes to `laravel-sapb1-toolkit` will be documented in this file.

## v2.8.0 - 2026-01-13

### Added

#### Observability & Async Support

**Sync Events (4 events)**
- `SyncStarted` - Dispatched when sync begins (entity, syncType, since)
- `SyncCompleted` - Dispatched on success (entity, SyncResult)
- `SyncFailed` - Dispatched on failure (entity, error, exception)
- `SyncProgress` - Progress reporting (processed, total, percentage)

**Queue Integration**
- `SyncEntityJob` - Queueable job for async sync operations
  - Auto-retry (3 attempts, 60s backoff)
  - Job tags for monitoring (sync, entity:*, full-sync/incremental-sync)
  - Support for full sync, incremental sync, and since-date sync

**Logging**
- Comprehensive logging in `LocalSyncService`
- Start/complete/fail logging with context
- Debug logging for delete detection

**Config Updates**
- `sync.dispatch_events` - Enable/disable event dispatching
- `sync.queue.*` - Queue configuration (connection, queue, tries, backoff)

### Usage

```php
// Async sync with queue
use SapB1\Toolkit\Jobs\SyncEntityJob;

SyncEntityJob::dispatch('Items');                    // Incremental
SyncEntityJob::dispatch('Items', fullSync: true);   // Full sync
SyncEntityJob::dispatch('Orders', since: '2026-01-01');

// Queue to specific queue
SyncEntityJob::dispatch('Items')->onQueue('sync');

// Listen to events
Event::listen(SyncCompleted::class, function ($event) {
    Log::info("Synced {$event->entity}: {$event->total()} records");
});

// Disable events
$syncService->withoutEvents()->sync('Items');
```

---

## v2.7.0 - 2026-01-12

### Added

#### Sync & Local Database
- `LocalSyncService` - SAP'tan local DB'ye sync orchestrator
- `SyncRegistry` - Entity configuration management
- `SyncConfig` - Predefined configs for 10 entities
- `SyncMetadata` Eloquent model - Sync state tracking
- `SyncResult` value object - Operation results
- `SyncException` - Error handling

#### Commands
- `sapb1:sync-setup` - Migration generator for sync tables
- `sapb1:sync-status` - Status monitoring command
- Updated `sapb1:sync` - Local DB sync support

#### Migration Stubs (11)
- `metadata.stub` - Sync metadata table
- `items.stub`, `business_partners.stub` - Master data
- `orders.stub`, `invoices.stub`, `delivery_notes.stub`, `quotations.stub`, `credit_notes.stub` - Sales
- `purchase_orders.stub`, `purchase_invoices.stub`, `goods_receipt_po.stub` - Purchase

### Usage

```php
// Setup migrations (one-time)
// php artisan sapb1:sync-setup Items BusinessPartners Orders

// Incremental sync
$result = $syncService->sync('Items');
// SyncResult { created: 10, updated: 140, deleted: 0, duration: 1.23s }

// Full sync with delete detection
$result = $syncService->fullSyncWithDeletes('Items');

// Artisan commands
// php artisan sapb1:sync Items --full
// php artisan sapb1:sync-status
```

---

## v2.6.0 - 2026-01-11

### Added

#### Change Tracking / Event Detection
- `ChangeTracker` - Polling-based change detection engine
- `WatcherConfig` - Fluent entity watch configuration
- `Change` value object - Represents detected changes
- `ChangeType` enum - Created, Updated, Deleted
- `StateStore` interface and `CacheStateStore` implementation
- `ChangesDetected` and `EntityChangeDetected` events
- `ChangeTrackingService` - Multi-entity orchestrator
- `sapb1:watch` Artisan command - CLI-based watching

### Usage

```php
$tracker = ChangeTracker::for('Orders')
    ->primaryKey('DocEntry')
    ->detectCreated(true)
    ->detectUpdated(true);

$changes = $tracker->poll();

// php artisan sapb1:watch Orders --interval=30
```

---

## v2.5.0 - 2026-01-11

### Added

#### Local Cache System
- `CacheResolver` - 5-level priority cache decision system
- `CacheManager` - Laravel Cache integration with tags
- `HasCache` trait for Models
- `QueryBuilder::cache()` and `noCache()` methods
- Entity-level cache configuration
- `CacheException` for error handling

### Priority Order
1. Query-level → `Item::cache(600)->find($id)`
2. Model-level → `protected static bool $cacheEnabled = true`
3. Entity config → `config('laravel-toolkit.cache.entities.Items.enabled')`
4. Global config → `config('laravel-toolkit.cache.enabled')` (default: false)

---

## v2.4.0 - 2026-01-11

### Added

#### UDF (User Defined Fields) Support
- `UdfService` - UserFieldsMD endpoint (read-only)
- Entity-to-Table mapping (40+ entities: Orders→ORDR, etc.)
- `HasUdf` trait for Models (getUdf, setUdf, getUdfs)
- Builder `udf()` method support
- `UdfException` for error handling

### Usage

```php
$order = Order::find(123);
$value = $order->getUdf('CustomField');
$order->setUdf('CustomField', 'value');
$order->save();
```

---

## v2.3.0 - 2026-01-08

### Added

#### Advanced Document Operations
- `DocumentActionService` - Close, Cancel, Reopen actions
- `DraftService` - Drafts endpoint management
- `DocumentType` enum enhancements
- Bulk operations with BatchRequest
- `DocumentActionException` and `DraftException`

### Usage

```php
$actionService->closeOrder(123);
$actionService->cancelInvoice(456);
$results = $actionService->closeOrders([123, 124, 125]);

$draft = $draftService->createOrderDraft($data);
$document = $draftService->saveAsDocument($draftEntry);
```

---

## v2.2.0 - 2026-01-08

### Added

#### Semantic Layer / Analytics
- `SemanticQueryService` - sml.svc endpoint wrapper
- `SemanticQueryServiceBuilder` - Fluent query builder
- Dimensions, measures, filters support
- Laravel Collection integration

---

## v2.1.0 - 2026-01-08

### Added

#### SDK Features Exposure
- `AttachmentService` - Attachments2 endpoint wrapper
- `BatchService` - $batch endpoint wrapper
- `SqlQueryService` - SQLQueries endpoint wrapper
- `HasAttachments` trait for Models

---

## v2.0.0 - 2026-01-03

### Added

#### Entity Layer - Eloquent-like ORM for SAP B1

A comprehensive ORM-like model layer that brings Eloquent-style syntax to SAP B1 entities.

##### Core Infrastructure (25 files)
- `SapB1Model` - Base abstract model class with CRUD operations
- `QueryBuilder` - OData query builder with Eloquent-like syntax
- `ModelCollection` - Collection class with filter, map, pluck, sum, avg, etc.
- `Paginator` - Pagination support with OData $top/$skip
- `ModelNotFoundException` - Exception for missing models

##### Traits (Concerns)
- `HasAttributes` - Attribute management, fill, __get/__set
- `HasCasting` - Attribute casting (integer, float, date, datetime, decimal, enum, etc.)
- `HasDirtyTracking` - Track changes for partial updates
- `HasEvents` - Model lifecycle events (creating, created, updating, updated, etc.)
- `HasQueryBuilder` - Static query methods (where, orderBy, limit, etc.)
- `HasRelationships` - Relationship definitions and loading

##### Relations
- `Relation` - Base relation class
- `HasMany` - One-to-many relationships
- `HasOne` - One-to-one relationships
- `BelongsTo` - Inverse relationships (N:1)

##### Casts (9 types)
- `AsBoolean`, `AsInteger`, `AsFloat`, `AsDecimal`
- `AsDate`, `AsDateTime`
- `AsArray`, `AsEnum`

##### Sales Models (12)
- `Order`, `Quotation`, `Invoice`, `Delivery`, `SalesReturn`, `CreditNote`
- `DownPayment`, `Draft`, `BlanketAgreement`
- `CorrectionInvoice`, `CorrectionInvoiceReversal`, `SalesTaxInvoice`

##### Purchase Models (11)
- `PurchaseOrder`, `PurchaseQuotation`, `GoodsReceipt`, `PurchaseInvoice`
- `PurchaseReturn`, `PurchaseCreditNote`, `PurchaseDownPayment`, `PurchaseRequest`
- `PurchaseTaxInvoice`, `CorrectionPurchaseInvoice`, `CorrectionPurchaseInvoiceReversal`

##### Essential Models
- `Partner` - Business Partner model with orders/invoices relationships
- `Item` - Item model with warehouse relationship
- `Warehouse` - Warehouse model

##### Line Models
- `DocumentLine` - Generic document line with item/warehouse relations
- `JournalEntryLine` - Journal entry line
- `PaymentInvoice` - Payment invoice line
- `BlanketAgreementItemLine` - Blanket agreement item line

### Usage Examples

```php
use SapB1\Toolkit\Models\Sales\Order;

// Find
$order = Order::find(123);

// Relationships (lazy loading)
$order->partner;       // BelongsTo Partner
$order->documentLines; // HasMany DocumentLine

// Query builder (Eloquent-like)
$orders = Order::where('DocTotal', '>', 1000)
    ->where('DocumentStatus', 'bost_Open')
    ->orderBy('DocDate', 'desc')
    ->with('partner')
    ->limit(10)
    ->get();

// Hybrid OData filter support
$orders = Order::filter("DocTotal gt 1000 and DocDate ge '2024-01-01'")
    ->orderBy('DocDate', 'desc')
    ->get();

// Scopes
$openOrders = Order::open()->get();
$customerOrders = Order::byCustomer('C001')->get();

// CRUD operations
$order = Order::create([
    'CardCode' => 'C001',
    'DocumentLines' => [...]
]);

$order->Comments = 'Updated';
$order->save();  // Only sends changed fields (dirty tracking)

// Domain methods
$delivery = $order->toDelivery();
$invoice = $order->toInvoice();
$order->close();
$order->cancel();
```

### Changed
- Total PHP files: 570+
- Total Models: 53 (Core: 25, Sales: 12, Purchase: 11, Lines: 4, Essential: 3)
- PHPStan Level 8 compliance maintained

### Tests
- All existing 1135+ tests passing
- PHPStan errors: 0

---

## v1.2.0 - 2026-01-03

### Added

#### Business Partner Module Extension (9 new entities)
- `BusinessPartnerGroupAction`, `BusinessPartnerGroupDto`, `BusinessPartnerGroupBuilder` - BP groups
- `SalesPersonAction`, `SalesPersonDto`, `SalesPersonBuilder` - Sales persons with commission tracking
- `TerritoryAction`, `TerritoryDto`, `TerritoryBuilder` - Territory management
- `IndustryAction`, `IndustryDto`, `IndustryBuilder` - Industry classifications
- `SalesOpportunityAction`, `SalesOpportunityDto`, `SalesOpportunityLineDto`, `SalesOpportunityBuilder` - CRM opportunities
- `SalesStageAction`, `SalesStageDto`, `SalesStageBuilder` - Sales pipeline stages
- `ContactAction`, `ContactBuilder` - Standalone contact employee management (reuses ContactPersonDto)
- `CampaignAction`, `CampaignDto`, `CampaignItemDto`, `CampaignBuilder` - Marketing campaigns
- `CampaignResponseTypeAction`, `CampaignResponseTypeDto`, `CampaignResponseTypeBuilder` - Campaign response types

#### Service Module (9 new entities)
- `ServiceCallAction`, `ServiceCallDto`, `ServiceCallBuilder` - Service call management with close action
- `ServiceContractAction`, `ServiceContractDto`, `ServiceContractLineDto`, `ServiceContractBuilder` - Service contracts with lines
- `ServiceCallOriginAction`, `ServiceCallOriginDto`, `ServiceCallOriginBuilder` - Call origins
- `ServiceCallTypeAction`, `ServiceCallTypeDto`, `ServiceCallTypeBuilder` - Call types
- `ServiceCallStatusAction`, `ServiceCallStatusDto`, `ServiceCallStatusBuilder` - Call statuses
- `ServiceCallSolutionStatusAction`, `ServiceCallSolutionStatusDto`, `ServiceCallSolutionStatusBuilder` - Solution statuses
- `ServiceCallProblemTypeAction`, `ServiceCallProblemTypeDto`, `ServiceCallProblemTypeBuilder` - Problem types
- `ServiceCallProblemSubTypeAction`, `ServiceCallProblemSubTypeDto`, `ServiceCallProblemSubTypeBuilder` - Problem sub-types
- `ServiceGroupAction`, `ServiceGroupDto`, `ServiceGroupBuilder` - Service groups

#### New Enums
- `ServiceCallPriority` - Low, Medium, High
- `OpportunityStatus` - Open, Won, Lost
- `CampaignStatus` - Draft, Active, Finished, Cancelled

### Changed
- Total entities increased from 54 to 72 (+18)
- Total DTOs: ~100 (including line DTOs)
- Total Builders: ~75
- Total Actions: ~75
- PHPStan Level 8 compliance maintained

### Tests
- 256 new unit tests for v1.2.0 entities
- DTO tests: fromArray, fromResponse, toArray, null filtering
- Builder tests: fluent interface, method chaining, line/item management
- Total test count: ~890+

---

## v1.1.0 - 2026-01-03

### Added

#### Package Rename
- Package renamed from `ismaildasci/laravel-toolkit` to `ismaildasci/laravel-sapb1-toolkit`

#### Inventory Module Extension (13 new entities)
- `BinLocationAction`, `BinLocationDto`, `BinLocationBuilder` - Bin location management
- `BatchNumberDetailAction`, `BatchNumberDetailDto`, `BatchNumberDetailBuilder` - Batch tracking
- `SerialNumberDetailAction`, `SerialNumberDetailDto`, `SerialNumberDetailBuilder` - Serial number tracking
- `InventoryGenEntryAction`, `InventoryGenEntryDto`, `InventoryGenEntryLineDto`, `InventoryGenEntryBuilder` - Goods Receipt
- `InventoryGenExitAction`, `InventoryGenExitDto`, `InventoryGenExitLineDto`, `InventoryGenExitBuilder` - Goods Issue
- `InventoryPostingAction`, `InventoryPostingDto`, `InventoryPostingLineDto`, `InventoryPostingBuilder` - Inventory posting
- `InventoryCountingAction`, `InventoryCountingDto`, `InventoryCountingLineDto`, `InventoryCountingBuilder` - Physical counting
- `InventoryCycleAction`, `InventoryCycleDto`, `InventoryCycleBuilder` - Cycle count configuration
- `InventoryTransferRequestAction`, `InventoryTransferRequestDto`, `InventoryTransferRequestLineDto`, `InventoryTransferRequestBuilder` - Transfer requests
- `InventoryOpeningBalanceAction`, `InventoryOpeningBalanceDto`, `InventoryOpeningBalanceLineDto`, `InventoryOpeningBalanceBuilder` - Opening balances
- `PickListAction`, `PickListDto`, `PickListLineDto`, `PickListBuilder` - Pick list management
- `CycleCountDeterminationAction`, `CycleCountDeterminationDto`, `CycleCountDeterminationBuilder` - Cycle count setup
- `StockTakingAction`, `StockTakingDto`, `StockTakingLineDto`, `StockTakingBuilder` - Stock taking

#### Finance Module Extension (22 new entities)
- `BankAction`, `BankDto`, `BankBuilder` - Bank master data
- `HouseBankAccountAction`, `HouseBankAccountDto`, `HouseBankAccountBuilder` - Company bank accounts
- `CurrencyAction`, `CurrencyDto`, `CurrencyBuilder` - Currency definitions
- `VatGroupAction`, `VatGroupDto`, `VatGroupBuilder` - VAT groups
- `WithholdingTaxCodeAction`, `WithholdingTaxCodeDto`, `WithholdingTaxCodeBuilder` - Withholding tax
- `SalesTaxCodeAction`, `SalesTaxCodeDto`, `SalesTaxCodeBuilder` - Sales tax codes
- `SalesTaxAuthorityAction`, `SalesTaxAuthorityDto`, `SalesTaxAuthorityBuilder` - Tax authorities
- `PaymentTermsTypeAction`, `PaymentTermsTypeDto`, `PaymentTermsTypeBuilder` - Payment terms
- `BankStatementAction`, `BankStatementDto`, `BankStatementRowDto`, `BankStatementBuilder` - Bank statements
- `BankPageAction`, `BankPageDto`, `BankPageBuilder` - Bank pages
- `DepositAction`, `DepositDto`, `DepositCheckDto`, `DepositCreditCardDto`, `DepositBuilder` - Deposits
- `CreditCardAction`, `CreditCardDto`, `CreditCardBuilder` - Credit card definitions
- `CreditCardPaymentAction`, `CreditCardPaymentDto`, `CreditCardPaymentBuilder` - Credit card payments
- `ChecksforPaymentAction`, `ChecksforPaymentDto`, `ChecksforPaymentBuilder` - Payment checks
- `CashFlowLineItemAction`, `CashFlowLineItemDto`, `CashFlowLineItemBuilder` - Cash flow items
- `CashDiscountAction`, `CashDiscountDto`, `CashDiscountBuilder` - Cash discounts
- `BudgetAction`, `BudgetDto`, `BudgetLineDto`, `BudgetBuilder` - Budget management
- `BudgetScenarioAction`, `BudgetScenarioDto`, `BudgetScenarioBuilder` - Budget scenarios
- `BudgetDistributionAction`, `BudgetDistributionDto`, `BudgetDistributionBuilder` - Budget distributions
- `FinancialYearAction`, `FinancialYearDto`, `FinancialYearBuilder` - Financial years
- `InternalReconciliationAction`, `InternalReconciliationDto`, `InternalReconciliationBuilder` - Internal reconciliations
- `PaymentDraftAction`, `PaymentDraftDto`, `PaymentDraftBuilder` - Payment drafts

### Changed
- Total entities increased from 19 to 54
- Total DTOs: 80 (including line DTOs)
- Total Builders: 57
- Total Actions: 56
- PHPStan Level 8 compliance maintained

### Tests
- Comprehensive unit test coverage for all new entities
- 97 test files, 634 tests total
- DTO tests: fromArray, fromResponse, toArray methods
- Builder tests: fluent interface, method chaining, reset functionality

---

## v1.0.0-stable - 2026-01-03

### Added
- Production-ready stable release
- 147 PHP files
- 178 tests, PHPStan Level 8
- CacheService for master data caching
- Integration test infrastructure

---

## v1.0.0-beta.2 - 2026-01-03

### Added

#### Down Payment Module
- `DownPaymentDto`, `DownPaymentBuilder`, `DownPaymentAction` for Sales
- `PurchaseDownPaymentDto`, `PurchaseDownPaymentBuilder`, `PurchaseDownPaymentAction` for Purchase
- `DownPaymentType` and `DownPaymentStatus` enums

#### Additional Enums
- `TaxCode` - Turkish tax codes (KDV0, KDV1, KDV8, KDV10, KDV18, KDV20, STOPAJ, EXEMPT)
- `Currency` - Common currencies with symbols (TRY, USD, EUR, GBP, etc.)
- `UnitOfMeasure` - Units of measure (PCS, KG, LT, M, M2, HR, etc.)

#### Generate Command
- `php artisan sapb1:generate {name}` - Scaffolds DTO, Builder, and Action files
- Options: `--module`, `--entity`, `--type`, `--force`
- Stub files for customization

#### Install Command
- `php artisan sapb1:install` - One-command package installation
- Publishes config and migrations
- Displays post-install instructions

#### Test Fixtures
- JSON fixtures for Orders, Invoices, BusinessPartners, Items, Payments, Warehouses, JournalEntries, DownPayments
- `FixtureLoader` helper class for loading test data

#### Unit Tests
- Enum tests (DocumentStatus, DocumentType, TaxCode, Currency, UnitOfMeasure, DownPaymentType, CardType)
- DTO tests (DocumentDto, DocumentLineDto)
- Builder tests (DocumentBuilder, DownPaymentBuilder)
- Feature tests (ServiceProvider, InstallCommand, GenerateCommand, FixtureLoader)
- **142 tests, 418 assertions**

### Fixed
- ServiceProvider migration registration (`hasMigration()`)
- Simplified service registration (removed unnecessary callbacks)
- PHPStan type safety in GenerateCommand

### Changed
- Removed empty `resources/views/` directory

---

## v1.0.0-beta.1 - 2026-01-03

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

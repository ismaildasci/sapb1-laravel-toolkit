# Changelog

All notable changes to `laravel-sapb1-toolkit` will be documented in this file.

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

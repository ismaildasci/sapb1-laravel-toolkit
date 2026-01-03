# SAP Business One Laravel Toolkit

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ismaildasci/laravel-toolkit.svg?style=flat-square)](https://packagist.org/packages/ismaildasci/laravel-toolkit)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/ismaildasci/laravel-toolkit/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/ismaildasci/laravel-toolkit/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/ismaildasci/laravel-toolkit/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/ismaildasci/laravel-toolkit/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/ismaildasci/laravel-toolkit.svg?style=flat-square)](https://packagist.org/packages/ismaildasci/laravel-toolkit)

A comprehensive Laravel toolkit for SAP Business One Service Layer integration. Provides Actions, DTOs, Builders, Services, and more for seamless SAP B1 operations.

## Features

- **Actions**: CRUD operations for all major SAP B1 entities (Orders, Invoices, Business Partners, etc.)
- **DTOs**: Type-safe Data Transfer Objects for request/response handling
- **Builders**: Fluent builders for creating complex documents
- **Services**: High-level orchestration for document flows, payments, inventory, and reporting
- **Enums**: 15+ enums covering document types, statuses, and SAP B1 constants
- **Events**: Document lifecycle events (created, updated, closed, cancelled)
- **Validation Rules**: Laravel validation rules for SAP B1 entities
- **Attribute Casts**: Eloquent casts for SAP B1 data types
- **Artisan Commands**: CLI tools for sync, cache, and reporting

## Requirements

- PHP 8.4+
- Laravel 11.x or 12.x
- [ismaildasci/laravel-sapb1](https://github.com/ismaildasci/laravel-sapb1) ^1.0

## Installation

```bash
composer require ismaildasci/laravel-toolkit
```

Publish the config file:

```bash
php artisan vendor:publish --tag="toolkit-config"
```

## Quick Start

### Using Actions

```php
use SapB1\Toolkit\Actions\Sales\OrderAction;

$orderAction = app(OrderAction::class);

// Create an order
$order = $orderAction->create([
    'CardCode' => 'C001',
    'DocDate' => now()->format('Y-m-d'),
    'DocumentLines' => [
        ['ItemCode' => 'ITEM001', 'Quantity' => 10, 'Price' => 100],
    ],
]);

// Find an order
$order = $orderAction->find(123);

// Update an order
$orderAction->update(123, ['Comments' => 'Updated comment']);

// Close an order
$orderAction->close(123);
```

### Using Builders

```php
use SapB1\Toolkit\Builders\Sales\OrderBuilder;

$order = OrderBuilder::make()
    ->cardCode('C001')
    ->docDate(now())
    ->comments('New order')
    ->addLine('ITEM001', 10, 100.00)
    ->addLine('ITEM002', 5, 50.00)
    ->build();

$orderAction->create($order);
```

### Using DTOs

```php
use SapB1\Toolkit\DTOs\Sales\OrderDto;

// From API response
$orderDto = OrderDto::fromResponse($apiResponse);

// Access typed properties
echo $orderDto->cardCode;
echo $orderDto->docTotal;

// Convert to array
$data = $orderDto->toArray();
```

### Using Services

```php
use SapB1\Toolkit\Services\DocumentFlowService;
use SapB1\Toolkit\Services\PaymentService;

// Document Flow
$documentFlow = app(DocumentFlowService::class);
$invoice = $documentFlow->orderToInvoice(123); // Convert order to invoice
$delivery = $documentFlow->orderToDelivery(123); // Convert order to delivery

// Payments
$paymentService = app(PaymentService::class);
$payment = $paymentService->receivePayment('C001', [
    ['docEntry' => 456, 'amount' => 1000],
]);
```

## Available Components

### Actions

| Module | Actions |
|--------|---------|
| Sales | `OrderAction`, `QuotationAction`, `DeliveryAction`, `InvoiceAction`, `CreditNoteAction`, `ReturnAction` |
| Purchase | `PurchaseOrderAction`, `GoodsReceiptAction`, `PurchaseInvoiceAction`, `PurchaseReturnAction` |
| Inventory | `ItemAction`, `WarehouseAction`, `StockTransferAction` |
| Finance | `PaymentAction`, `JournalEntryAction` |
| Business Partner | `BusinessPartnerAction`, `ActivityAction` |

### Builders

| Module | Builders |
|--------|----------|
| Sales | `OrderBuilder`, `QuotationBuilder`, `DeliveryBuilder`, `InvoiceBuilder`, `CreditNoteBuilder`, `ReturnBuilder` |
| Purchase | `PurchaseOrderBuilder`, `GoodsReceiptBuilder`, `PurchaseInvoiceBuilder`, `PurchaseReturnBuilder` |
| Inventory | `ItemBuilder`, `WarehouseBuilder`, `StockTransferBuilder` |
| Finance | `PaymentBuilder`, `JournalEntryBuilder` |
| Business Partner | `BusinessPartnerBuilder`, `ActivityBuilder` |

### Services

| Service | Description |
|---------|-------------|
| `DocumentFlowService` | Convert documents (order to invoice, order to delivery, etc.) |
| `PaymentService` | Receive/make payments, apply to invoices |
| `InventoryService` | Stock transfers, batch operations, stock queries |
| `ReportingService` | Sales/purchase summaries, aging reports, top customers/items |
| `ApprovalService` | Approval workflow management |
| `SyncService` | Full and incremental data synchronization |

### Validation Rules

```php
use SapB1\Toolkit\Rules\CardCodeRule;
use SapB1\Toolkit\Rules\ItemCodeRule;
use SapB1\Toolkit\Rules\DocEntryRule;
use SapB1\Toolkit\Rules\WarehouseCodeRule;
use SapB1\Toolkit\Rules\AccountCodeRule;

$request->validate([
    'customer' => ['required', new CardCodeRule()],
    'item' => ['required', new ItemCodeRule(mustBeSalesItem: true)],
    'order_id' => ['required', new DocEntryRule('Orders')],
    'warehouse' => ['required', new WarehouseCodeRule()],
    'account' => ['required', new AccountCodeRule()],
]);
```

### Attribute Casts

```php
use SapB1\Toolkit\Casts\SapDateCast;
use SapB1\Toolkit\Casts\SapBooleanCast;
use SapB1\Toolkit\Casts\MoneyAmountCast;
use SapB1\Toolkit\Casts\DocumentTypeCast;
use SapB1\Toolkit\Casts\CardTypeCast;

class Order extends Model
{
    protected $casts = [
        'doc_date' => SapDateCast::class,
        'is_closed' => SapBooleanCast::class,
        'doc_total' => MoneyAmountCast::class,
        'doc_type' => DocumentTypeCast::class,
    ];
}
```

### Events

```php
use SapB1\Toolkit\Events\DocumentCreated;
use SapB1\Toolkit\Events\DocumentUpdated;
use SapB1\Toolkit\Events\DocumentClosed;
use SapB1\Toolkit\Events\DocumentCancelled;
use SapB1\Toolkit\Events\PaymentReceived;
use SapB1\Toolkit\Events\ApprovalRequested;
use SapB1\Toolkit\Events\ApprovalCompleted;

// Listen for events
Event::listen(DocumentCreated::class, function ($event) {
    Log::info("Document created: {$event->entity} #{$event->docEntry}");
});
```

### Artisan Commands

```bash
# Test SAP B1 connection
php artisan sapb1:test --connection=default

# Sync data from SAP B1
php artisan sapb1:sync Items --full
php artisan sapb1:sync BusinessPartners

# Manage cache
php artisan sapb1:cache warm
php artisan sapb1:cache clear

# Generate reports
php artisan sapb1:report sales --from=2024-01-01 --to=2024-12-31
php artisan sapb1:report aging
php artisan sapb1:report top-customers --limit=10
```

## Exception Handling

```php
use SapB1\Toolkit\Exceptions\DocumentNotFoundException;
use SapB1\Toolkit\Exceptions\ValidationException;
use SapB1\Toolkit\Exceptions\ConnectionException;
use SapB1\Toolkit\Exceptions\InsufficientStockException;
use SapB1\Toolkit\Exceptions\ApprovalRequiredException;

try {
    $order = $orderAction->find(999);
} catch (DocumentNotFoundException $e) {
    // Handle not found
} catch (ValidationException $e) {
    // Handle validation errors
    $errors = $e->getContext()['errors'];
} catch (ApprovalRequiredException $e) {
    // Handle approval workflow
    $approvalCode = $e->approvalRequestCode;
}
```

## Testing

```bash
composer test
```

## Static Analysis

```bash
composer analyse
```

## Code Formatting

```bash
composer format
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Ismail Dasci](https://github.com/ismaildasci)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

# SAP Business One Laravel Toolkit

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ismaildasci/laravel-sapb1-toolkit.svg?style=flat-square)](https://packagist.org/packages/ismaildasci/laravel-sapb1-toolkit)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/ismaildasci/laravel-sapb1-toolkit/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/ismaildasci/laravel-sapb1-toolkit/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/ismaildasci/laravel-sapb1-toolkit/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/ismaildasci/laravel-sapb1-toolkit/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/ismaildasci/laravel-sapb1-toolkit.svg?style=flat-square)](https://packagist.org/packages/ismaildasci/laravel-sapb1-toolkit)
[![PHP Version](https://img.shields.io/packagist/php-v/ismaildasci/laravel-sapb1-toolkit.svg?style=flat-square)](https://packagist.org/packages/ismaildasci/laravel-sapb1-toolkit)
[![License](https://img.shields.io/packagist/l/ismaildasci/laravel-sapb1-toolkit.svg?style=flat-square)](LICENSE.md)

A complete business logic toolkit for SAP Business One Service Layer integration in Laravel. Built on top of [laravel-sapb1](https://github.com/ismaildasci/laravel-sapb1).

## Features

| Component | Count | Description |
|-----------|-------|-------------|
| **Models** | 54 | Eloquent-like ORM for SAP B1 entities |
| **Actions** | 110 | CRUD operations for all SAP B1 entities |
| **Builders** | 122+ | Fluent document construction |
| **DTOs** | 145+ | Type-safe data transfer objects |
| **Services** | 17 | Business logic orchestration |
| **Enums** | 36 | SAP B1 constants and status codes |
| **Commands** | 7 | Artisan CLI commands |
| **Cache** | 2 | Priority-based caching system |

**Modules:** Sales, Purchase, Inventory, Finance, Business Partner, Production, HR, Admin, Service

### Key Capabilities

- **Eloquent-like ORM** - Query SAP B1 entities with familiar Laravel syntax
- **UDF Support** - Read/write User Defined Fields on any entity
- **Local Caching** - Priority-based cache with entity-level configuration
- **Change Tracking** - Polling-based change detection for SAP entities
- **Local Database Sync** - Sync SAP data to local database with soft deletes

## Requirements

- PHP 8.4+
- Laravel 11.x or 12.x
- [laravel-sapb1](https://github.com/ismaildasci/laravel-sapb1) ^1.0

## Installation

```bash
composer require ismaildasci/laravel-sapb1-toolkit
```

```bash
php artisan vendor:publish --tag="sapb1-toolkit-config"
```

## Quick Start

### Eloquent-like Models

```php
use SapB1\Toolkit\Models\Sales\Order;

$orders = Order::where('DocTotal', '>', 1000)
    ->where('DocumentStatus', 'bost_Open')
    ->orderBy('DocDate', 'desc')
    ->with('partner')
    ->get();

$order = Order::create([
    'CardCode' => 'C001',
    'DocumentLines' => [
        ['ItemCode' => 'ITEM001', 'Quantity' => 10, 'Price' => 100],
    ],
]);
```

### Actions

```php
use SapB1\Toolkit\Actions\Sales\OrderAction;

$orderAction = app(OrderAction::class);

$order = $orderAction->create([
    'CardCode' => 'C001',
    'DocumentLines' => [
        ['ItemCode' => 'ITEM001', 'Quantity' => 10, 'Price' => 100],
    ],
]);

$orderAction->close(123);
```

### Builders

```php
use SapB1\Toolkit\Builders\Sales\OrderBuilder;

$data = OrderBuilder::create()
    ->cardCode('C001')
    ->docDate('2024-01-15')
    ->addLine(fn ($line) => $line
        ->itemCode('ITEM001')
        ->quantity(10)
        ->price(100)
    )
    ->build();
```

### Services

```php
use SapB1\Toolkit\Services\DocumentFlowService;

$flow = app(DocumentFlowService::class);
$invoice = $flow->orderToInvoice(123);
$delivery = $flow->orderToDelivery(123);
```

### UDF Support (v2.4+)

```php
use SapB1\Toolkit\Models\Sales\Order;

$order = Order::find(123);

// Read UDFs
$value = $order->getUdf('CustomField');
$allUdfs = $order->getUdfs();

// Write UDFs
$order->setUdf('CustomField', 'value');
$order->save();

// Builder support
$data = OrderBuilder::create()
    ->cardCode('C001')
    ->udf('CustomField', 'value')
    ->build();
```

### Local Cache (v2.5+)

```php
use SapB1\Toolkit\Models\Inventory\Item;

// Query-level cache control
$item = Item::cache()->find('A001');           // Enable with default TTL
$item = Item::cache(600)->find('A001');        // 10 minute TTL
$item = Item::noCache()->find('A001');         // Disable cache

// Flush cache
Item::flushCache();
Item::forgetCached('A001');
```

### Change Tracking (v2.6+)

```php
use SapB1\Toolkit\ChangeTracking\ChangeTracker;

$tracker = ChangeTracker::for('Orders')
    ->primaryKey('DocEntry')
    ->detectCreated(true)
    ->detectUpdated(true);

$changes = $tracker->poll();

foreach ($changes as $change) {
    if ($change->isCreated()) {
        // Handle new order
    }
}
```

### Local Database Sync (v2.7+)

```bash
# Create migrations for entities you want to sync
php artisan sapb1:sync-setup Items BusinessPartners Orders

# Run migrations
php artisan migrate

# Sync data
php artisan sapb1:sync Items                    # Incremental sync
php artisan sapb1:sync Items --full             # Full sync with delete detection
php artisan sapb1:sync-status                   # Check sync status
```

```php
use SapB1\Toolkit\Sync\LocalSyncService;

$syncService = app(LocalSyncService::class);

// Sync to local database
$result = $syncService->sync('Items');
// SyncResult { created: 10, updated: 140, deleted: 0, duration: 1.23s }

// Full sync with soft delete detection
$result = $syncService->fullSyncWithDeletes('Items');

// Scheduler integration
$schedule->command('sapb1:sync Items')->hourly();
$schedule->command('sapb1:sync Items --full')->weekly();
```

## Artisan Commands

| Command | Description |
|---------|-------------|
| `sapb1:sync {entity}` | Sync SAP data to local database |
| `sapb1:sync-setup {entities}` | Create sync migrations |
| `sapb1:sync-status` | Show sync status |
| `sapb1:watch {entity}` | Watch for entity changes |
| `sapb1:cache` | Manage entity cache |
| `sapb1:test-connection` | Test SAP B1 connection |
| `sapb1:generate` | Generate toolkit components |

## Documentation

| Topic | Description |
|-------|-------------|
| [Installation](docs/installation.md) | Setup and configuration |
| [Models](docs/models.md) | Eloquent-like ORM |
| [Actions](docs/actions.md) | CRUD operations |
| [Builders](docs/builders.md) | Fluent document builders |
| [DTOs](docs/dtos.md) | Data transfer objects |
| [Services](docs/services.md) | Business logic |
| [Enums](docs/enums.md) | SAP B1 constants |
| [Validation](docs/validation.md) | Laravel validation rules |
| [Exceptions](docs/exceptions.md) | Error handling |

## Directory Structure

```
src/
├── Actions/          # CRUD operations (110 files)
├── Builders/         # Fluent builders (122+ files)
├── DTOs/             # Data Transfer Objects (145+ files)
├── Models/           # Eloquent-like ORM (54 files)
├── Services/         # Business logic (17 files)
├── Enums/            # SAP B1 constants (36 files)
├── Cache/            # Caching infrastructure
├── ChangeTracking/   # Change detection system
├── Sync/             # Local database sync
├── Events/           # Document lifecycle events
├── Exceptions/       # Domain-specific exceptions
├── Rules/            # Laravel validation rules
├── Casts/            # Attribute casts
└── Commands/         # Artisan commands (7 files)
```

## Testing

```bash
composer test        # Run tests
composer analyse     # Static analysis
composer format      # Code formatting
```

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for version history.

## Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md) for guidelines.

## Security

Report vulnerabilities via [security policy](../../security/policy).

## License

MIT License. See [LICENSE.md](LICENSE.md) for details.

---

**Author:** [İsmail Daşcı](https://github.com/ismaildasci)

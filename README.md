# SAP Business One Laravel Toolkit

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ismaildasci/laravel-sapb1-toolkit.svg?style=flat-square)](https://packagist.org/packages/ismaildasci/laravel-sapb1-toolkit)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/ismaildasci/laravel-sapb1-toolkit/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/ismaildasci/laravel-sapb1-toolkit/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/ismaildasci/laravel-sapb1-toolkit/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/ismaildasci/laravel-sapb1-toolkit/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/ismaildasci/laravel-sapb1-toolkit.svg?style=flat-square)](https://packagist.org/packages/ismaildasci/laravel-sapb1-toolkit)
[![PHP Version](https://img.shields.io/packagist/php-v/ismaildasci/laravel-sapb1-toolkit.svg?style=flat-square)](https://packagist.org/packages/ismaildasci/laravel-sapb1-toolkit)
[![License](https://img.shields.io/packagist/l/ismaildasci/laravel-sapb1-toolkit.svg?style=flat-square)](LICENSE.md)

A business logic layer for SAP Business One Service Layer integration in Laravel. Built on top of [laravel-sapb1](https://github.com/ismaildasci/laravel-sapb1).

## Features

| Component | Count | Description |
|-----------|-------|-------------|
| **Models** | 54 | Eloquent-like ORM for SAP B1 entities |
| **Actions** | 110 | CRUD operations for all SAP B1 entities |
| **Builders** | 117 | Fluent document construction |
| **DTOs** | 146 | Type-safe data transfer objects |
| **Services** | 14 | Business logic orchestration |
| **Enums** | 36 | SAP B1 constants and status codes |

**Modules:** Sales, Purchase, Inventory, Finance, Business Partner, Production, HR, Admin, Service

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
├── Builders/         # Fluent builders (117 files)
├── DTOs/             # Data Transfer Objects (146 files)
├── Models/           # Eloquent-like ORM (54 files)
├── Services/         # Business logic (14 files)
├── Enums/            # SAP B1 constants (36 files)
├── Events/           # Document lifecycle events
├── Exceptions/       # Domain-specific exceptions
├── Rules/            # Laravel validation rules
├── Casts/            # Attribute casts
└── Commands/         # Artisan commands
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

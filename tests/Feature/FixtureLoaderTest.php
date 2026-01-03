<?php

declare(strict_types=1);

use SapB1\Toolkit\Tests\Fixtures\FixtureLoader;

beforeEach(function () {
    FixtureLoader::resetBasePath();
});

describe('load method', function () {
    it('loads a fixture file', function () {
        $data = FixtureLoader::load('orders');

        expect($data)->toBeArray();
        expect($data)->toHaveKeys(['single', 'list', 'created']);
    });

    it('loads a specific key from fixture', function () {
        $single = FixtureLoader::load('orders', 'single');

        expect($single)->toBeArray();
        expect($single)->toHaveKey('DocEntry');
        expect($single)->toHaveKey('CardCode');
    });

    it('throws exception for non-existent fixture', function () {
        FixtureLoader::load('non_existent_fixture');
    })->throws(RuntimeException::class, 'Fixture file not found');

    it('throws exception for non-existent key', function () {
        FixtureLoader::load('orders', 'invalid_key');
    })->throws(RuntimeException::class, "Key 'invalid_key' not found");
});

describe('single method', function () {
    it('loads single entity from orders', function () {
        $order = FixtureLoader::single('orders');

        expect($order)->toBeArray();
        expect($order['DocEntry'])->toBe(1);
        expect($order['DocNum'])->toBe(1001);
        expect($order['CardCode'])->toBe('C001');
    });

    it('loads single entity from invoices', function () {
        $invoice = FixtureLoader::single('invoices');

        expect($invoice)->toBeArray();
        expect($invoice['DocEntry'])->toBe(1);
        expect($invoice['DocType'])->toBe('dDocument_Items');
    });

    it('loads single entity from business_partners', function () {
        $bp = FixtureLoader::single('business_partners');

        expect($bp)->toBeArray();
        expect($bp['CardCode'])->toBe('C001');
        expect($bp['CardType'])->toBe('cCustomer');
    });

    it('loads single entity from items', function () {
        $item = FixtureLoader::single('items');

        expect($item)->toBeArray();
        expect($item['ItemCode'])->toBe('ITEM001');
        expect($item['ItemName'])->toBe('Test Product A');
    });

    it('loads single entity from warehouses', function () {
        $warehouse = FixtureLoader::single('warehouses');

        expect($warehouse)->toBeArray();
        expect($warehouse['WarehouseCode'])->toBe('WH01');
        expect($warehouse['WarehouseName'])->toBe('Main Warehouse');
    });

    it('loads single entity from journal_entries', function () {
        $je = FixtureLoader::single('journal_entries');

        expect($je)->toBeArray();
        expect($je['JdtNum'])->toBe(1);
        expect($je)->toHaveKey('JournalEntryLines');
    });
});

describe('list method', function () {
    it('loads list with metadata from orders', function () {
        $list = FixtureLoader::list('orders');

        expect($list)->toBeArray();
        expect($list)->toHaveKey('value');
        expect($list)->toHaveKey('odata.nextLink');
    });

    it('loads list with values from invoices', function () {
        $list = FixtureLoader::list('invoices');

        expect($list['value'])->toBeArray();
        expect($list['value'])->toHaveCount(2);
    });
});

describe('listValues method', function () {
    it('returns only the value array from orders', function () {
        $values = FixtureLoader::listValues('orders');

        expect($values)->toBeArray();
        expect($values)->toHaveCount(3);
        expect($values[0])->toHaveKey('DocEntry');
    });

    it('returns only the value array from business_partners', function () {
        $values = FixtureLoader::listValues('business_partners');

        expect($values)->toBeArray();
        expect($values)->toHaveCount(3);
    });
});

describe('created method', function () {
    it('loads created response from orders', function () {
        $created = FixtureLoader::created('orders');

        expect($created)->toBeArray();
        expect($created['DocEntry'])->toBe(4);
        expect($created['DocNum'])->toBe(1004);
    });

    it('loads created response from items', function () {
        $created = FixtureLoader::created('items');

        expect($created)->toBeArray();
        expect($created['ItemCode'])->toBe('ITEM004');
    });
});

describe('error method', function () {
    it('loads error response from orders', function () {
        $error = FixtureLoader::error('orders');

        expect($error)->toBeArray();
        expect($error)->toHaveKey('error');
        expect($error['error'])->toHaveKey('code');
        expect($error['error'])->toHaveKey('message');
    });

    it('loads error with code from orders', function () {
        $error = FixtureLoader::error('orders');

        expect($error['error']['code'])->toBe('-2028');
        expect($error['error']['message']['value'])->toContain('Business partner code');
    });
});

describe('payments fixture', function () {
    it('loads incoming payment', function () {
        $data = FixtureLoader::load('payments', 'incoming');

        expect($data)->toHaveKey('single');
        expect($data['single']['DocType'])->toBe('rCustomer');
    });

    it('loads outgoing payment', function () {
        $data = FixtureLoader::load('payments', 'outgoing');

        expect($data)->toHaveKey('single');
        expect($data['single']['DocType'])->toBe('rSupplier');
    });

    it('loads payment methods', function () {
        $data = FixtureLoader::load('payments', 'methods');

        expect($data)->toHaveKeys(['cash', 'transfer', 'check', 'credit_card']);
    });
});

describe('available method', function () {
    it('returns list of available fixtures', function () {
        $available = FixtureLoader::available();

        expect($available)->toBeArray();
        expect($available)->toContain('orders');
        expect($available)->toContain('invoices');
        expect($available)->toContain('business_partners');
        expect($available)->toContain('items');
        expect($available)->toContain('payments');
        expect($available)->toContain('warehouses');
        expect($available)->toContain('journal_entries');
    });
});

describe('custom base path', function () {
    it('allows setting custom base path', function () {
        $customPath = __DIR__.'/../Fixtures';
        FixtureLoader::setBasePath($customPath);

        // Should still work with custom path pointing to same location
        $data = FixtureLoader::load('orders');
        expect($data)->toBeArray();

        FixtureLoader::resetBasePath();
    });

    it('resets base path to default', function () {
        FixtureLoader::setBasePath('/some/custom/path');
        FixtureLoader::resetBasePath();

        // Should work with default path
        $data = FixtureLoader::load('orders');
        expect($data)->toBeArray();
    });
});

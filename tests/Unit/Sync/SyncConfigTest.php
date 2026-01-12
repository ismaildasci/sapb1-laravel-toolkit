<?php

declare(strict_types=1);

use SapB1\Toolkit\Sync\SyncConfig;

describe('SyncConfig', function () {
    describe('constructor', function () {
        it('creates config with required parameters', function () {
            $config = new SyncConfig(
                entity: 'Items',
                table: 'sap_items',
                primaryKey: 'ItemCode',
            );

            expect($config->entity)->toBe('Items');
            expect($config->table)->toBe('sap_items');
            expect($config->primaryKey)->toBe('ItemCode');
        });

        it('has default values', function () {
            $config = new SyncConfig(
                entity: 'Items',
                table: 'sap_items',
                primaryKey: 'ItemCode',
            );

            expect($config->columns)->toBe([]);
            expect($config->updateDateField)->toBe('UpdateDate');
            expect($config->batchSize)->toBe(5000);
            expect($config->syncLines)->toBeFalse();
            expect($config->trackDeletes)->toBeTrue();
        });
    });

    describe('predefined configs', function () {
        it('creates Items config', function () {
            $config = SyncConfig::items();

            expect($config->entity)->toBe('Items');
            expect($config->table)->toBe('sap_items');
            expect($config->primaryKey)->toBe('ItemCode');
            expect($config->columns)->toContain('ItemCode', 'ItemName', 'ItemType');
            expect($config->syncLines)->toBeFalse();
        });

        it('creates BusinessPartners config', function () {
            $config = SyncConfig::businessPartners();

            expect($config->entity)->toBe('BusinessPartners');
            expect($config->table)->toBe('sap_business_partners');
            expect($config->primaryKey)->toBe('CardCode');
            expect($config->columns)->toContain('CardCode', 'CardName', 'CardType');
        });

        it('creates Orders config with lines', function () {
            $config = SyncConfig::orders();

            expect($config->entity)->toBe('Orders');
            expect($config->table)->toBe('sap_orders');
            expect($config->primaryKey)->toBe('DocEntry');
            expect($config->syncLines)->toBeTrue();
            expect($config->linesTable)->toBe('sap_order_lines');
            expect($config->lineColumns)->toContain('DocEntry', 'LineNum', 'ItemCode');
        });

        it('creates Invoices config with lines', function () {
            $config = SyncConfig::invoices();

            expect($config->entity)->toBe('Invoices');
            expect($config->table)->toBe('sap_invoices');
            expect($config->syncLines)->toBeTrue();
        });

        it('creates DeliveryNotes config with lines', function () {
            $config = SyncConfig::deliveryNotes();

            expect($config->entity)->toBe('DeliveryNotes');
            expect($config->table)->toBe('sap_delivery_notes');
            expect($config->syncLines)->toBeTrue();
        });

        it('creates Quotations config with lines', function () {
            $config = SyncConfig::quotations();

            expect($config->entity)->toBe('Quotations');
            expect($config->table)->toBe('sap_quotations');
            expect($config->syncLines)->toBeTrue();
        });

        it('creates CreditNotes config with lines', function () {
            $config = SyncConfig::creditNotes();

            expect($config->entity)->toBe('CreditNotes');
            expect($config->table)->toBe('sap_credit_notes');
            expect($config->syncLines)->toBeTrue();
        });

        it('creates PurchaseOrders config with lines', function () {
            $config = SyncConfig::purchaseOrders();

            expect($config->entity)->toBe('PurchaseOrders');
            expect($config->table)->toBe('sap_purchase_orders');
            expect($config->syncLines)->toBeTrue();
        });

        it('creates PurchaseInvoices config with lines', function () {
            $config = SyncConfig::purchaseInvoices();

            expect($config->entity)->toBe('PurchaseInvoices');
            expect($config->table)->toBe('sap_purchase_invoices');
            expect($config->syncLines)->toBeTrue();
        });

        it('creates GoodsReceiptPO config with lines', function () {
            $config = SyncConfig::goodsReceiptPO();

            expect($config->entity)->toBe('PurchaseDeliveryNotes');
            expect($config->table)->toBe('sap_goods_receipt_po');
            expect($config->syncLines)->toBeTrue();
        });
    });

    describe('all', function () {
        it('returns all predefined configs', function () {
            $all = SyncConfig::all();

            expect($all)->toHaveCount(10);
            expect($all)->toHaveKeys([
                'Items', 'BusinessPartners', 'Orders', 'Invoices',
                'DeliveryNotes', 'Quotations', 'CreditNotes',
                'PurchaseOrders', 'PurchaseInvoices', 'GoodsReceiptPO',
            ]);
        });
    });

    describe('availableEntities', function () {
        it('returns list of entity names', function () {
            $entities = SyncConfig::availableEntities();

            expect($entities)->toContain('Items', 'BusinessPartners', 'Orders');
            expect($entities)->toHaveCount(10);
        });
    });

    describe('isAvailable', function () {
        it('returns true for available entities', function () {
            expect(SyncConfig::isAvailable('Items'))->toBeTrue();
            expect(SyncConfig::isAvailable('Orders'))->toBeTrue();
        });

        it('returns false for unknown entities', function () {
            expect(SyncConfig::isAvailable('Unknown'))->toBeFalse();
            expect(SyncConfig::isAvailable('invalid'))->toBeFalse();
        });
    });

    describe('for', function () {
        it('returns config for available entity', function () {
            $config = SyncConfig::for('Items');

            expect($config)->toBeInstanceOf(SyncConfig::class);
            expect($config->entity)->toBe('Items');
        });

        it('returns null for unknown entity', function () {
            expect(SyncConfig::for('Unknown'))->toBeNull();
        });
    });

    describe('with', function () {
        it('creates copy with modified properties', function () {
            $original = SyncConfig::items();
            $modified = $original->with(['batchSize' => 10000]);

            expect($modified->batchSize)->toBe(10000);
            expect($modified->entity)->toBe($original->entity);
            expect($original->batchSize)->toBe(5000);
        });

        it('can modify multiple properties', function () {
            $original = SyncConfig::items();
            $modified = $original->with([
                'batchSize' => 1000,
                'trackDeletes' => false,
                'filter' => "Valid eq 'Y'",
            ]);

            expect($modified->batchSize)->toBe(1000);
            expect($modified->trackDeletes)->toBeFalse();
            expect($modified->filter)->toBe("Valid eq 'Y'");
        });
    });

    describe('toArray', function () {
        it('converts config to array', function () {
            $config = SyncConfig::items();
            $array = $config->toArray();

            expect($array)->toHaveKeys([
                'entity', 'table', 'primaryKey', 'columns',
                'updateDateField', 'batchSize', 'syncLines',
                'linesTable', 'lineColumns', 'filter', 'trackDeletes',
            ]);

            expect($array['entity'])->toBe('Items');
            expect($array['table'])->toBe('sap_items');
        });
    });
});

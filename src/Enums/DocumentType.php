<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Enums;

enum DocumentType: int
{
    case SalesQuotation = 23;
    case SalesOrder = 17;
    case DeliveryNote = 15;
    case Return = 16;
    case ARInvoice = 13;
    case ARCreditNote = 14;
    case ARDownPayment = 203;
    case PurchaseQuotation = 540000006;
    case PurchaseOrder = 22;
    case GoodsReceiptPO = 20;
    case PurchaseReturn = 21;
    case APInvoice = 18;
    case APCreditNote = 19;
    case APDownPayment = 204;
    case InventoryGenEntry = 59;
    case InventoryGenExit = 60;
    case StockTransfer = 67;

    public function label(): string
    {
        return match ($this) {
            self::SalesQuotation => 'Sales Quotation',
            self::SalesOrder => 'Sales Order',
            self::DeliveryNote => 'Delivery Note',
            self::Return => 'Return',
            self::ARInvoice => 'A/R Invoice',
            self::ARCreditNote => 'A/R Credit Note',
            self::ARDownPayment => 'A/R Down Payment',
            self::PurchaseQuotation => 'Purchase Quotation',
            self::PurchaseOrder => 'Purchase Order',
            self::GoodsReceiptPO => 'Goods Receipt PO',
            self::PurchaseReturn => 'Purchase Return',
            self::APInvoice => 'A/P Invoice',
            self::APCreditNote => 'A/P Credit Note',
            self::APDownPayment => 'A/P Down Payment',
            self::InventoryGenEntry => 'Inventory Gen. Entry',
            self::InventoryGenExit => 'Inventory Gen. Exit',
            self::StockTransfer => 'Stock Transfer',
        };
    }

    public function isSalesDocument(): bool
    {
        return in_array($this, [
            self::SalesQuotation,
            self::SalesOrder,
            self::DeliveryNote,
            self::Return,
            self::ARInvoice,
            self::ARCreditNote,
            self::ARDownPayment,
        ], true);
    }

    public function isPurchaseDocument(): bool
    {
        return in_array($this, [
            self::PurchaseQuotation,
            self::PurchaseOrder,
            self::GoodsReceiptPO,
            self::PurchaseReturn,
            self::APInvoice,
            self::APCreditNote,
            self::APDownPayment,
        ], true);
    }

    public function isInventoryDocument(): bool
    {
        return in_array($this, [
            self::InventoryGenEntry,
            self::InventoryGenExit,
            self::StockTransfer,
        ], true);
    }
}

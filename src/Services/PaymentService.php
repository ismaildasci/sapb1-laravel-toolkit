<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Services;

use SapB1\Toolkit\Actions\Finance\PaymentAction;
use SapB1\Toolkit\Builders\Finance\PaymentBuilder;
use SapB1\Toolkit\DTOs\Finance\PaymentDto;

/**
 * Service for SAP B1 payment operations.
 *
 * Handles incoming and outgoing payments, invoice payments,
 * and balance inquiries. Supports multiple payment methods
 * including transfer, cash, and check.
 *
 * @example
 * ```php
 * $service = app(PaymentService::class);
 * $payment = $service->payInvoice(123, 1000.00, 'transfer', 'BANK01');
 * ```
 */
final class PaymentService extends BaseService
{
    /**
     * Create incoming payment for an invoice.
     */
    public function payInvoice(
        int $invoiceDocEntry,
        float $amount,
        string $paymentMethod = 'transfer',
        ?string $accountCode = null
    ): PaymentDto {
        $invoice = $this->client()
            ->service('Invoices')
            ->find($invoiceDocEntry);

        $builder = PaymentBuilder::create()
            ->cardCode($invoice['CardCode'])
            ->docDate(date('Y-m-d'))
            ->addInvoice([
                'DocEntry' => $invoiceDocEntry,
                'SumApplied' => $amount,
                'InvoiceType' => 13,
            ]);

        if ($accountCode !== null) {
            match ($paymentMethod) {
                'transfer' => $builder->transferAccount($accountCode)->transferSum($amount),
                'cash' => $builder->cashAccount($accountCode)->cashSum($amount),
                'check' => $builder->checkAccount($accountCode)->checkSum($amount),
                default => $builder->transferAccount($accountCode)->transferSum($amount),
            };
        }

        return (new PaymentAction)
            ->connection($this->connection)
            ->incoming()
            ->create($builder);
    }

    /**
     * Create outgoing payment for a purchase invoice.
     */
    public function payPurchaseInvoice(
        int $invoiceDocEntry,
        float $amount,
        string $paymentMethod = 'transfer',
        ?string $accountCode = null
    ): PaymentDto {
        $invoice = $this->client()
            ->service('PurchaseInvoices')
            ->find($invoiceDocEntry);

        $builder = PaymentBuilder::create()
            ->cardCode($invoice['CardCode'])
            ->docDate(date('Y-m-d'))
            ->addInvoice([
                'DocEntry' => $invoiceDocEntry,
                'SumApplied' => $amount,
                'InvoiceType' => 18,
            ]);

        if ($accountCode !== null) {
            match ($paymentMethod) {
                'transfer' => $builder->transferAccount($accountCode)->transferSum($amount),
                'cash' => $builder->cashAccount($accountCode)->cashSum($amount),
                'check' => $builder->checkAccount($accountCode)->checkSum($amount),
                default => $builder->transferAccount($accountCode)->transferSum($amount),
            };
        }

        return (new PaymentAction)
            ->connection($this->connection)
            ->outgoing()
            ->create($builder);
    }

    /**
     * Pay multiple invoices at once.
     *
     * @param  array<array{docEntry: int, amount: float}>  $invoices
     */
    public function payMultipleInvoices(
        string $cardCode,
        array $invoices,
        string $paymentMethod = 'transfer',
        ?string $accountCode = null
    ): PaymentDto {
        $builder = PaymentBuilder::create()
            ->cardCode($cardCode)
            ->docDate(date('Y-m-d'));

        $totalAmount = 0;

        foreach ($invoices as $invoice) {
            $builder->addInvoice([
                'DocEntry' => $invoice['docEntry'],
                'SumApplied' => $invoice['amount'],
                'InvoiceType' => 13,
            ]);
            $totalAmount += $invoice['amount'];
        }

        if ($accountCode !== null) {
            match ($paymentMethod) {
                'transfer' => $builder->transferAccount($accountCode)->transferSum($totalAmount),
                'cash' => $builder->cashAccount($accountCode)->cashSum($totalAmount),
                'check' => $builder->checkAccount($accountCode)->checkSum($totalAmount),
                default => $builder->transferAccount($accountCode)->transferSum($totalAmount),
            };
        }

        return (new PaymentAction)
            ->connection($this->connection)
            ->create($builder);
    }

    /**
     * Get outstanding balance for a business partner.
     */
    public function getOutstandingBalance(string $cardCode): float
    {
        $bp = $this->client()
            ->service('BusinessPartners')
            ->find($cardCode);

        return (float) ($bp['CurrentAccountBalance'] ?? 0);
    }

    /**
     * Get unpaid invoices for a business partner.
     *
     * @return array<array<string, mixed>>
     */
    public function getUnpaidInvoices(string $cardCode): array
    {
        $response = $this->client()
            ->service('Invoices')
            ->queryBuilder()
            ->filter("CardCode eq '{$cardCode}' and DocumentStatus eq 'bost_Open' and PaidToDate lt DocTotal")
            ->select(['DocEntry', 'DocNum', 'DocDate', 'DocDueDate', 'DocTotal', 'PaidToDate'])
            ->get();

        return $response['value'] ?? [];
    }
}

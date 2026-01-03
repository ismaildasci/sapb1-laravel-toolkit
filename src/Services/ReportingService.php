<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Services;

/**
 * Service for reporting operations.
 */
final class ReportingService extends BaseService
{
    /**
     * Get sales summary by period.
     *
     * @return array<string, mixed>
     */
    public function getSalesSummary(string $fromDate, string $toDate): array
    {
        $invoices = $this->client()
            ->service('Invoices')
            ->queryBuilder()
            ->filter("DocDate ge '{$fromDate}' and DocDate le '{$toDate}'")
            ->select(['DocTotal', 'VatSum', 'DocCurrency'])
            ->get();

        $total = 0;
        $vatTotal = 0;
        $count = 0;

        foreach ($invoices['value'] ?? [] as $invoice) {
            $total += (float) ($invoice['DocTotal'] ?? 0);
            $vatTotal += (float) ($invoice['VatSum'] ?? 0);
            $count++;
        }

        return [
            'period' => ['from' => $fromDate, 'to' => $toDate],
            'invoiceCount' => $count,
            'totalSales' => $total,
            'totalVat' => $vatTotal,
            'netSales' => $total - $vatTotal,
        ];
    }

    /**
     * Get top customers by revenue.
     *
     * @return array<array<string, mixed>>
     */
    public function getTopCustomers(string $fromDate, string $toDate, int $limit = 10): array
    {
        $invoices = $this->client()
            ->service('Invoices')
            ->queryBuilder()
            ->filter("DocDate ge '{$fromDate}' and DocDate le '{$toDate}'")
            ->select(['CardCode', 'CardName', 'DocTotal'])
            ->get();

        $customers = [];

        foreach ($invoices['value'] ?? [] as $invoice) {
            $cardCode = $invoice['CardCode'];
            if (! isset($customers[$cardCode])) {
                $customers[$cardCode] = [
                    'CardCode' => $cardCode,
                    'CardName' => $invoice['CardName'],
                    'TotalRevenue' => 0,
                    'InvoiceCount' => 0,
                ];
            }
            $customers[$cardCode]['TotalRevenue'] += (float) ($invoice['DocTotal'] ?? 0);
            $customers[$cardCode]['InvoiceCount']++;
        }

        usort($customers, fn ($a, $b) => $b['TotalRevenue'] <=> $a['TotalRevenue']);

        return array_slice($customers, 0, $limit);
    }

    /**
     * Get top selling items.
     *
     * @return array<array<string, mixed>>
     */
    public function getTopSellingItems(string $fromDate, string $toDate, int $limit = 10): array
    {
        $invoices = $this->client()
            ->service('Invoices')
            ->queryBuilder()
            ->filter("DocDate ge '{$fromDate}' and DocDate le '{$toDate}'")
            ->select(['DocumentLines'])
            ->get();

        $items = [];

        foreach ($invoices['value'] ?? [] as $invoice) {
            foreach ($invoice['DocumentLines'] ?? [] as $line) {
                $itemCode = $line['ItemCode'] ?? '';
                if (empty($itemCode)) {
                    continue;
                }

                if (! isset($items[$itemCode])) {
                    $items[$itemCode] = [
                        'ItemCode' => $itemCode,
                        'ItemDescription' => $line['ItemDescription'] ?? '',
                        'TotalQuantity' => 0,
                        'TotalRevenue' => 0,
                    ];
                }
                $items[$itemCode]['TotalQuantity'] += (float) ($line['Quantity'] ?? 0);
                $items[$itemCode]['TotalRevenue'] += (float) ($line['LineTotal'] ?? 0);
            }
        }

        usort($items, fn ($a, $b) => $b['TotalRevenue'] <=> $a['TotalRevenue']);

        return array_slice($items, 0, $limit);
    }

    /**
     * Get aging report for receivables.
     *
     * @return array<string, mixed>
     */
    public function getAgingReport(?string $cardCode = null): array
    {
        $filter = "DocumentStatus eq 'bost_Open' and PaidToDate lt DocTotal";

        if ($cardCode !== null) {
            $filter .= " and CardCode eq '{$cardCode}'";
        }

        $invoices = $this->client()
            ->service('Invoices')
            ->queryBuilder()
            ->filter($filter)
            ->select(['DocEntry', 'DocNum', 'CardCode', 'CardName', 'DocDate', 'DocDueDate', 'DocTotal', 'PaidToDate'])
            ->get();

        $today = strtotime(date('Y-m-d'));
        $aging = [
            'current' => ['count' => 0, 'total' => 0],
            '1-30' => ['count' => 0, 'total' => 0],
            '31-60' => ['count' => 0, 'total' => 0],
            '61-90' => ['count' => 0, 'total' => 0],
            'over90' => ['count' => 0, 'total' => 0],
        ];

        $details = [];

        foreach ($invoices['value'] ?? [] as $invoice) {
            $dueDate = strtotime($invoice['DocDueDate']);
            $outstanding = (float) ($invoice['DocTotal'] ?? 0) - (float) ($invoice['PaidToDate'] ?? 0);
            $daysPastDue = max(0, (int) (($today - $dueDate) / 86400));

            $bucket = match (true) {
                $daysPastDue <= 0 => 'current',
                $daysPastDue <= 30 => '1-30',
                $daysPastDue <= 60 => '31-60',
                $daysPastDue <= 90 => '61-90',
                default => 'over90',
            };

            $aging[$bucket]['count']++;
            $aging[$bucket]['total'] += $outstanding;

            $details[] = [
                'DocEntry' => $invoice['DocEntry'],
                'DocNum' => $invoice['DocNum'],
                'CardCode' => $invoice['CardCode'],
                'CardName' => $invoice['CardName'],
                'DocDate' => $invoice['DocDate'],
                'DocDueDate' => $invoice['DocDueDate'],
                'Outstanding' => $outstanding,
                'DaysPastDue' => $daysPastDue,
                'Bucket' => $bucket,
            ];
        }

        return [
            'summary' => $aging,
            'totalOutstanding' => array_sum(array_column($aging, 'total')),
            'details' => $details,
        ];
    }

    /**
     * Get purchase summary by period.
     *
     * @return array<string, mixed>
     */
    public function getPurchaseSummary(string $fromDate, string $toDate): array
    {
        $invoices = $this->client()
            ->service('PurchaseInvoices')
            ->queryBuilder()
            ->filter("DocDate ge '{$fromDate}' and DocDate le '{$toDate}'")
            ->select(['DocTotal', 'VatSum', 'DocCurrency'])
            ->get();

        $total = 0;
        $vatTotal = 0;
        $count = 0;

        foreach ($invoices['value'] ?? [] as $invoice) {
            $total += (float) ($invoice['DocTotal'] ?? 0);
            $vatTotal += (float) ($invoice['VatSum'] ?? 0);
            $count++;
        }

        return [
            'period' => ['from' => $fromDate, 'to' => $toDate],
            'invoiceCount' => $count,
            'totalPurchases' => $total,
            'totalVat' => $vatTotal,
            'netPurchases' => $total - $vatTotal,
        ];
    }
}

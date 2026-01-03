<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Commands;

use Illuminate\Console\Command;
use SapB1\Toolkit\Services\ReportingService;

class ReportCommand extends Command
{
    protected $signature = 'sapb1:report
                            {type : The report type (sales, purchases, aging, top-customers, top-items)}
                            {--from= : Start date (Y-m-d)}
                            {--to= : End date (Y-m-d)}
                            {--limit=10 : Limit for top reports}
                            {--connection=default : The SAP B1 connection to use}';

    protected $description = 'Generate SAP B1 reports';

    public function handle(ReportingService $reportingService): int
    {
        /** @var string $type */
        $type = $this->argument('type');
        /** @var string $connection */
        $connection = $this->option('connection') ?? 'default';
        /** @var string $from */
        $from = $this->option('from') ?? date('Y-m-01');
        /** @var string $to */
        $to = $this->option('to') ?? date('Y-m-d');
        $limit = (int) $this->option('limit');

        $reportingService->connection($connection);

        return match ($type) {
            'sales' => $this->salesReport($reportingService, $from, $to),
            'purchases' => $this->purchaseReport($reportingService, $from, $to),
            'aging' => $this->agingReport($reportingService),
            'top-customers' => $this->topCustomersReport($reportingService, $from, $to, $limit),
            'top-items' => $this->topItemsReport($reportingService, $from, $to, $limit),
            default => $this->invalidType($type),
        };
    }

    private function salesReport(ReportingService $service, string $from, string $to): int
    {
        $this->info("Sales Report: {$from} to {$to}");

        $summary = $service->getSalesSummary($from, $to);

        $this->table(
            ['Metric', 'Value'],
            [
                ['Invoice Count', $summary['invoiceCount']],
                ['Total Sales', number_format($summary['totalSales'], 2)],
                ['Total VAT', number_format($summary['totalVat'], 2)],
                ['Net Sales', number_format($summary['netSales'], 2)],
            ]
        );

        return self::SUCCESS;
    }

    private function purchaseReport(ReportingService $service, string $from, string $to): int
    {
        $this->info("Purchase Report: {$from} to {$to}");

        $summary = $service->getPurchaseSummary($from, $to);

        $this->table(
            ['Metric', 'Value'],
            [
                ['Invoice Count', $summary['invoiceCount']],
                ['Total Purchases', number_format($summary['totalPurchases'], 2)],
                ['Total VAT', number_format($summary['totalVat'], 2)],
                ['Net Purchases', number_format($summary['netPurchases'], 2)],
            ]
        );

        return self::SUCCESS;
    }

    private function agingReport(ReportingService $service): int
    {
        $this->info('Aging Report');

        $report = $service->getAgingReport();

        $this->table(
            ['Bucket', 'Count', 'Total'],
            [
                ['Current', $report['summary']['current']['count'], number_format($report['summary']['current']['total'], 2)],
                ['1-30 Days', $report['summary']['1-30']['count'], number_format($report['summary']['1-30']['total'], 2)],
                ['31-60 Days', $report['summary']['31-60']['count'], number_format($report['summary']['31-60']['total'], 2)],
                ['61-90 Days', $report['summary']['61-90']['count'], number_format($report['summary']['61-90']['total'], 2)],
                ['Over 90 Days', $report['summary']['over90']['count'], number_format($report['summary']['over90']['total'], 2)],
            ]
        );

        $this->line('');
        $this->info('Total Outstanding: '.number_format($report['totalOutstanding'], 2));

        return self::SUCCESS;
    }

    private function topCustomersReport(ReportingService $service, string $from, string $to, int $limit): int
    {
        $this->info("Top {$limit} Customers: {$from} to {$to}");

        $customers = $service->getTopCustomers($from, $to, $limit);

        $rows = array_map(fn ($c) => [
            $c['CardCode'],
            $c['CardName'],
            number_format($c['TotalRevenue'], 2),
            $c['InvoiceCount'],
        ], $customers);

        $this->table(['Code', 'Name', 'Revenue', 'Invoices'], $rows);

        return self::SUCCESS;
    }

    private function topItemsReport(ReportingService $service, string $from, string $to, int $limit): int
    {
        $this->info("Top {$limit} Items: {$from} to {$to}");

        $items = $service->getTopSellingItems($from, $to, $limit);

        $rows = array_map(fn ($i) => [
            $i['ItemCode'],
            $i['ItemDescription'],
            number_format($i['TotalQuantity'], 2),
            number_format($i['TotalRevenue'], 2),
        ], $items);

        $this->table(['Code', 'Description', 'Qty Sold', 'Revenue'], $rows);

        return self::SUCCESS;
    }

    private function invalidType(string $type): int
    {
        $this->error("Invalid report type: {$type}");
        $this->line('Available types: sales, purchases, aging, top-customers, top-items');

        return self::FAILURE;
    }
}

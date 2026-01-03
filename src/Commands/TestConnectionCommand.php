<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Commands;

use Illuminate\Console\Command;
use SapB1\Facades\SapB1;

class TestConnectionCommand extends Command
{
    protected $signature = 'sapb1:test
                            {--connection=default : The connection to test}';

    protected $description = 'Test the SAP Business One connection';

    public function handle(): int
    {
        /** @var string $connection */
        $connection = $this->option('connection') ?? 'default';

        $this->info("Testing connection: {$connection}");

        try {
            /** @var mixed $client */
            $client = SapB1::connection($connection);

            // Try to fetch company info
            /** @var array<string, mixed> $companyInfo */
            $companyInfo = $client->service('CompanyInfo')->get();

            $this->info('Connection successful!');
            $this->table(
                ['Property', 'Value'],
                [
                    ['Company Name', $companyInfo['CompanyName'] ?? 'N/A'],
                    ['Version', $companyInfo['Version'] ?? 'N/A'],
                    ['Enable Advances', $companyInfo['EnableAdvances'] ?? 'N/A'],
                ]
            );

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Connection failed: '.$e->getMessage());

            return self::FAILURE;
        }
    }
}

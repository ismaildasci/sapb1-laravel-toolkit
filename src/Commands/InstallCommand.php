<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    protected $signature = 'sapb1:install
                            {--force : Overwrite existing files}';

    protected $description = 'Install the SAP B1 Toolkit package';

    public function handle(): int
    {
        $this->info('Installing SAP B1 Toolkit...');
        $this->newLine();

        // Publish config file
        $this->publishConfig();

        // Publish migrations
        $this->publishMigrations();

        // Ask to run migrations
        if ($this->confirm('Do you want to run the migrations now?', true)) {
            $this->runMigrations();
        }

        $this->newLine();
        $this->info('SAP B1 Toolkit installed successfully!');
        $this->newLine();

        $this->displayPostInstallInstructions();

        return self::SUCCESS;
    }

    private function publishConfig(): void
    {
        $this->components->task('Publishing configuration', function () {
            $params = [
                '--provider' => 'SapB1\Toolkit\ToolkitServiceProvider',
                '--tag' => 'laravel-toolkit-config',
            ];

            if ($this->option('force')) {
                $params['--force'] = true;
            }

            $this->callSilently('vendor:publish', $params);

            return true;
        });
    }

    private function publishMigrations(): void
    {
        $this->components->task('Publishing migrations', function () {
            $params = [
                '--provider' => 'SapB1\Toolkit\ToolkitServiceProvider',
                '--tag' => 'laravel-toolkit-migrations',
            ];

            if ($this->option('force')) {
                $params['--force'] = true;
            }

            $this->callSilently('vendor:publish', $params);

            return true;
        });
    }

    private function runMigrations(): void
    {
        $this->components->task('Running migrations', function () {
            $this->callSilently('migrate');

            return true;
        });
    }

    private function displayPostInstallInstructions(): void
    {
        $this->components->info('Next steps:');
        $this->newLine();

        $this->line('  1. Configure your SAP B1 connection in <comment>config/laravel-toolkit.php</comment>');
        $this->line('  2. Set environment variables in <comment>.env</comment>:');
        $this->newLine();
        $this->line('     <comment>SAPB1_BASE_URL=https://your-sap-server:50000/b1s/v1</comment>');
        $this->line('     <comment>SAPB1_COMPANY_DB=your_company_db</comment>');
        $this->line('     <comment>SAPB1_USERNAME=manager</comment>');
        $this->line('     <comment>SAPB1_PASSWORD=your_password</comment>');
        $this->newLine();
        $this->line('  3. Test your connection:');
        $this->line('     <comment>php artisan sapb1:test</comment>');
        $this->newLine();

        $this->components->info('Documentation: https://github.com/ismaildasci/laravel-toolkit');
    }
}

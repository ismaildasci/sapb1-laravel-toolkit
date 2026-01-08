<?php

declare(strict_types=1);

namespace SapB1\Toolkit;

use SapB1\Toolkit\Commands\CacheCommand;
use SapB1\Toolkit\Commands\GenerateCommand;
use SapB1\Toolkit\Commands\InstallCommand;
use SapB1\Toolkit\Commands\ReportCommand;
use SapB1\Toolkit\Commands\SyncCommand;
use SapB1\Toolkit\Commands\TestConnectionCommand;
use SapB1\Toolkit\Services\ApprovalService;
use SapB1\Toolkit\Services\AttachmentService;
use SapB1\Toolkit\Services\BatchService;
use SapB1\Toolkit\Services\CacheService;
use SapB1\Toolkit\Services\DocumentFlowService;
use SapB1\Toolkit\Services\InventoryService;
use SapB1\Toolkit\Services\PaymentService;
use SapB1\Toolkit\Services\ReportingService;
use SapB1\Toolkit\Services\SqlQueryService;
use SapB1\Toolkit\Services\SyncService;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ToolkitServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-toolkit')
            ->hasConfigFile('laravel-toolkit')
            ->hasMigration('create_toolkit_table')
            ->hasCommands([
                InstallCommand::class,
                GenerateCommand::class,
                TestConnectionCommand::class,
                SyncCommand::class,
                CacheCommand::class,
                ReportCommand::class,
            ]);
    }

    public function packageRegistered(): void
    {
        $this->registerServices();
    }

    public function packageBooted(): void
    {
        //
    }

    private function registerServices(): void
    {
        // Register services as singletons - Laravel can auto-resolve classes without dependencies
        $this->app->singleton(CacheService::class);
        $this->app->singleton(DocumentFlowService::class);
        $this->app->singleton(PaymentService::class);
        $this->app->singleton(InventoryService::class);
        $this->app->singleton(ReportingService::class);
        $this->app->singleton(ApprovalService::class);
        $this->app->singleton(SyncService::class);

        // v2.1.0 - SDK Features Exposure
        $this->app->singleton(AttachmentService::class);
        $this->app->singleton(BatchService::class);
        $this->app->singleton(SqlQueryService::class);
    }
}

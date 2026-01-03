<?php

declare(strict_types=1);

namespace SapB1\Toolkit;

use SapB1\Toolkit\Commands\CacheCommand;
use SapB1\Toolkit\Commands\ReportCommand;
use SapB1\Toolkit\Commands\SyncCommand;
use SapB1\Toolkit\Commands\TestConnectionCommand;
use SapB1\Toolkit\Services\ApprovalService;
use SapB1\Toolkit\Services\DocumentFlowService;
use SapB1\Toolkit\Services\InventoryService;
use SapB1\Toolkit\Services\PaymentService;
use SapB1\Toolkit\Services\ReportingService;
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
            ->hasCommands([
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
        $this->app->singleton(DocumentFlowService::class, function ($app) {
            return new DocumentFlowService;
        });

        $this->app->singleton(PaymentService::class, function ($app) {
            return new PaymentService;
        });

        $this->app->singleton(InventoryService::class, function ($app) {
            return new InventoryService;
        });

        $this->app->singleton(ReportingService::class, function ($app) {
            return new ReportingService;
        });

        $this->app->singleton(ApprovalService::class, function ($app) {
            return new ApprovalService;
        });

        $this->app->singleton(SyncService::class, function ($app) {
            return new SyncService;
        });
    }
}

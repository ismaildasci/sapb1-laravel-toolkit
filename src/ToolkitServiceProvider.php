<?php

declare(strict_types=1);

namespace SapB1\Toolkit;

use SapB1\MultiTenant\TenantManager;
use SapB1\Toolkit\ChangeTracking\ChangeTrackingService;
use SapB1\Toolkit\Commands\CacheCommand;
use SapB1\Toolkit\Commands\GenerateCommand;
use SapB1\Toolkit\Commands\InstallCommand;
use SapB1\Toolkit\Commands\ReportCommand;
use SapB1\Toolkit\Commands\SyncCommand;
use SapB1\Toolkit\Commands\SyncSetupCommand;
use SapB1\Toolkit\Commands\SyncStatusCommand;
use SapB1\Toolkit\Commands\TestConnectionCommand;
use SapB1\Toolkit\Commands\WatchCommand;
use SapB1\Toolkit\MultiTenant\MultiTenantService;
use SapB1\Toolkit\MultiTenant\Resolvers\AuthUserTenantResolver;
use SapB1\Toolkit\MultiTenant\Resolvers\ConfigTenantResolver;
use SapB1\Toolkit\MultiTenant\Resolvers\HeaderTenantResolver;
use SapB1\Toolkit\Services\ApprovalService;
use SapB1\Toolkit\Services\AttachmentService;
use SapB1\Toolkit\Services\BatchService;
use SapB1\Toolkit\Services\CacheService;
use SapB1\Toolkit\Services\DocumentActionService;
use SapB1\Toolkit\Services\DocumentFlowService;
use SapB1\Toolkit\Services\DraftService;
use SapB1\Toolkit\Services\InventoryService;
use SapB1\Toolkit\Services\PaymentService;
use SapB1\Toolkit\Services\ReportingService;
use SapB1\Toolkit\Services\SemanticQueryService;
use SapB1\Toolkit\Services\SqlQueryService;
use SapB1\Toolkit\Services\SyncService;
use SapB1\Toolkit\Services\UdfService;
use SapB1\Toolkit\Sync\LocalSyncService;
use SapB1\Toolkit\Sync\SyncRegistry;
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
                SyncSetupCommand::class,
                SyncStatusCommand::class,
                CacheCommand::class,
                ReportCommand::class,
                WatchCommand::class,
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

        // v2.2.0 - Semantic Layer / Analytics
        $this->app->singleton(SemanticQueryService::class);

        // v2.3.0 - Advanced Document Operations
        $this->app->singleton(DocumentActionService::class);
        $this->app->singleton(DraftService::class);

        // v2.4.0 - User Defined Fields
        $this->app->singleton(UdfService::class);

        // v2.6.0 - Change Tracking
        $this->app->singleton(ChangeTrackingService::class);

        // v2.7.0 - Local Database Sync
        $this->app->singleton(SyncRegistry::class);
        $this->app->singleton(LocalSyncService::class, function ($app) {
            return new LocalSyncService($app->make(SyncRegistry::class));
        });

        // v2.9.0 - Multi-Tenant Support
        $this->registerMultiTenant();
    }

    private function registerMultiTenant(): void
    {
        // Register TenantManager as singleton
        $this->app->singleton(TenantManager::class);

        // Register resolvers
        $this->app->singleton(ConfigTenantResolver::class);
        $this->app->singleton(HeaderTenantResolver::class);
        $this->app->singleton(AuthUserTenantResolver::class);

        // Register MultiTenantService with resolver configuration
        $this->app->singleton(MultiTenantService::class, function ($app) {
            $service = new MultiTenantService($app->make(TenantManager::class));

            // Configure resolver based on config
            if (config('laravel-toolkit.multi_tenant.enabled', false)) {
                $resolverType = config('laravel-toolkit.multi_tenant.resolver', 'config');

                $resolver = match ($resolverType) {
                    'header' => $app->make(HeaderTenantResolver::class),
                    'user' => $app->make(AuthUserTenantResolver::class),
                    'config' => $app->make(ConfigTenantResolver::class),
                    default => is_string($resolverType) && class_exists($resolverType)
                        ? $app->make($resolverType)
                        : $app->make(ConfigTenantResolver::class),
                };

                $service->setResolver($resolver);

                // Register tenants from config
                $service->registerFromConfig();
            }

            return $service;
        });
    }
}

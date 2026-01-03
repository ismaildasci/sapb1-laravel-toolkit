<?php

namespace SapB1\Toolkit\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use SapB1\SapB1ServiceProvider;
use SapB1\Toolkit\ToolkitServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        $this->loadEnvTesting();

        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'SapB1\\Toolkit\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function loadEnvTesting(): void
    {
        $envFile = __DIR__.'/../.env.testing';

        if (file_exists($envFile)) {
            $dotenv = \Dotenv\Dotenv::createImmutable(dirname($envFile), '.env.testing');
            $dotenv->safeLoad();
        }
    }

    protected function getPackageProviders($app)
    {
        return [
            SapB1ServiceProvider::class,
            ToolkitServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        // Load SAP B1 config from environment
        config()->set('sap-b1.connections.default', [
            'base_url' => env('SAP_B1_URL'),
            'company_db' => env('SAP_B1_COMPANY_DB'),
            'username' => env('SAP_B1_USERNAME'),
            'password' => env('SAP_B1_PASSWORD'),
            'language' => env('SAP_B1_LANGUAGE', 23),
        ]);

        config()->set('sap-b1.http.verify', env('SAP_B1_VERIFY_SSL', true));
    }
}

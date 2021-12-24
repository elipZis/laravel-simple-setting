<?php

namespace ElipZis\Setting\Tests;

use ElipZis\Setting\SettingServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra {
    protected function setUp(): void {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn(string $modelName) => 'ElipZis\\Setting\\Database\\Factories\\' . class_basename($modelName) . 'Factory'
        );
    }

    protected function getPackageProviders($app) {
        return [
            SettingServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app) {
        config()->set('database.default', 'testing');

        $migration = include __DIR__ . '/../database/migrations/create_settings_table.php.stub';
        $migration->up();

    }

    public function refreshServiceProvider(): void {
        (new SettingServiceProvider($this->app))->packageBooted();
    }
}

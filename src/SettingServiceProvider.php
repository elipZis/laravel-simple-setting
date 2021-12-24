<?php

namespace ElipZis\Setting;

use ElipZis\Setting\Commands\SyncSettingsCommand;
use ElipZis\Setting\Repositories\SettingRepository;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

/**
 * Configuring the package.
 *
 * This class is a Package Service Provider
 * More info: https://github.com/spatie/laravel-package-tools
 */
class SettingServiceProvider extends PackageServiceProvider
{
    /**
     * @param Package $package
     * @return void
     */
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-simple-setting')
            ->hasConfigFile()
            ->hasMigration('create_settings_table')
            ->hasRoute('setting')
            ->hasCommand(SyncSettingsCommand::class);
    }

    /**
     * @return void
     */
    public function packageRegistered(): void
    {
        $this->app->singleton(SettingRepository::class, fn () => new SettingRepository());
        $this->app->bind('simple-setting', SettingRepository::class);
    }
}

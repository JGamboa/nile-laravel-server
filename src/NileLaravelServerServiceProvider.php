<?php

namespace JGamboa\NileLaravelServer;

use Illuminate\Support\Facades\Config;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use JGamboa\NileLaravelServer\Commands\NileLaravelServerCommand;

class NileLaravelServerServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-nile-server')
            ->hasConfigFile('nile-laravel-server')
            ->hasViews()
            ->hasMigration('create_nile_laravel_server_data')
            ->hasCommand(NileLaravelServerCommand::class)
            ->hasRoute('api')
            ->hasInstallCommand(function (InstallCommand $command) {
            $command
                ->publishConfigFile()
                ->publishMigrations()
                ->askToRunMigrations()
                ->copyAndRegisterServiceProviderInApp()
                ->askToStarRepoOnGitHub('JGamboa/nile-laravel-server');
        });
    }

    public function bootingPackage(): void
    {
        if (Config::get('database.default') === 'pgsql') {
            Config::set('database.connections.pgsql.options', [\PDO::ATTR_EMULATE_PREPARES => true]);
        }
    }
}

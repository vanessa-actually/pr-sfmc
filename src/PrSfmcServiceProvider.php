<?php

namespace Dmgroup\PrSfmc;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Dmgroup\PrSfmc\Commands\PrSfmcCommand;

class PrSfmcServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('pr-sfmc')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_sfmc_transmissions_table')
            ->hasMigration('create_transmittable_table')
            ->hasCommand(PrSfmcCommand::class);
    }
}

<?php

namespace Alpayklncrsln\RuleSchema;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Alpayklncrsln\RuleSchema\Commands\RuleSchemaCommand;

class RuleSchemaServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('rule-schema')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_rule_schema_table')
            ->hasCommand(RuleSchemaCommand::class);
    }
}

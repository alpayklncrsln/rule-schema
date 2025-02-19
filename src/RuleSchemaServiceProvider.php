<?php

namespace Alpayklncrsln\RuleSchema;

use Alpayklncrsln\RuleSchema\Commands\AuthAllCommand;
use Alpayklncrsln\RuleSchema\Commands\AuthCommand;
use Alpayklncrsln\RuleSchema\Commands\AuthLoginCommand;
use Alpayklncrsln\RuleSchema\Commands\AuthRegisterCommand;
use Alpayklncrsln\RuleSchema\Commands\AuthResetPasswordCommand;
use Alpayklncrsln\RuleSchema\Commands\AuthUpdatePasswordCommand;
use Alpayklncrsln\RuleSchema\Commands\RuleSchemaCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

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
            ->hasCommands([
                RuleSchemaCommand::class,
                AuthCommand::class,
                AuthAllCommand::class,
                AuthRegisterCommand::class,
                AuthLoginCommand::class,
                AuthUpdatePasswordCommand::class,
                AuthResetPasswordCommand::class
            ]);
    }
}
